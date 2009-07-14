package Net::Top::Request;

use strict; 
use warnings;
use List::MoreUtils qw(uniq);
use Net::Top::Response;
use Return::Value;
use Log::Log4perl qw(:easy);
use Data::Dumper qw(Dumper);
use Carp;

my @COMMON_PARAMS = qw( format );

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my $self = {};
    bless $self, $class;
    my %params = @_;
    for ( keys %params ) {
        $self->set($_, $params{$_});
    }
    return $self;
}

# default http method
sub _http_method { 'get' }

# expand tags to plain fields
sub _expand_fields {
    my $fields = shift;
    my $expanded = {};
    for ( keys %$fields ) {
        _expand($fields, $_, $expanded) if !exists $expanded->{$_};
    }
    if ( !exists $fields->{':all'} ) {
        $fields->{':all'} = uniq(map { @{$_} } values %{$fields});
    }
}

sub _expand {
    my ($fields, $tag, $expanded) = @_;
    my @all;
    for ( @{$fields->{$tag}} ) {
        if ( substr($_, 0, 1) eq ':') {
            if ( !exists $expanded->{$_} ) {
                _expand($fields, $_, $expanded);
            }
            push @all, @{$fields->{$_}};
        } else {
            push @all, $_;
        }
    }
    $fields->{$tag} = [ uniq(@all) ];
    $expanded->{$tag}++;
}

sub _hook_for_set {
    my ($self, $field) = @_;
    my %hooks = (
        'fields' => sub {
            my ($self, $name, $fields) = @_;
            if ( ref $fields eq 'ARRAY' ) {
                my (@all, @tags);
                for ( @$fields ) {
                    if ( substr($_, 0, 1) eq ':') {
                        push @tags, $_;
                    } else {
                        push @all, $_;
                    }
                }
                push @all, $self->_fields(@tags);
                return join(',', uniq(@all));
            } else {
                return $fields;
            }
        },
    );
    return $hooks{$field};
}

sub _hook_for_get { }

sub make_request {
    my $class = shift;
    no strict 'refs';
    my %args = @_;
    my (@query_params, %query_params);

    ## sub _class_data
    *{$class.'::_class_data'} = sub { return \%args };
    
    ## sub _api_method
    if ( !exists $args{api_method} ) {
        croak "Unknown api method for '$class'";
    }
    *{$class.'::_api_method'} = sub { $args{api_method} };
    ## sub _http_method
    if ( exists $args{http_method} ) {
        *{$class.'::_http_method'} = sub { $args{http_method} };
    }
    ## sub check
    *{$class.'::check'} = sub {
        my $self = shift;
        if ( exists $args{require_params} ) {
            for ( @{$args{require_params}} ) {
                if ( !defined $self->get($_) ) {
                    return failure("Miss required field '$_'");
                }
            }
        }
        if ( $self->has('fields') ) {
            my $fields = $self->get('fields');
            return if !$fields;
            my %fields = map { $_ => 1 } $self->_fields(':all');
            foreach ( split(',', $fields) ) {
                if ( !exists $fields{$_} ) {
                    return failure("Unknown field '$_'");
                }
            }
        }
        return 1;
    };
    ## sub _query_params
    if ( exists $args{require_params} ) {
        push @query_params, @{$args{require_params}};
    }
    if ( exists $args{optional_params} ) {
        push @query_params, @{$args{optional_params}};
    }
    if ( exists $args{file_params} ) {
        push @query_params, @{$args{file_params}};
        my $sub = sub { return [ $_[2] ] }; # @_: $self, $field, $val
        for ( @{$args{file_params}} ) {
            if ( !exists $args{set_hooks}{$_} ) {
                $args{set_hooks}{$_} = $sub;
            }
        }
    }
    push @query_params, @COMMON_PARAMS;
    %query_params = map { $_ => 1 } @query_params;
    *{$class.'::_query_params'} = sub {
        if ( wantarray ) {
            return keys %query_params;
        } else {
            return { %query_params };
        }
    };
    ## sub has
    *{$class.'::has'} = sub { return exists $query_params{$_[1]} };
    ## sub _hook_for_set
    if ( exists $args{set_hooks} ) {
        ### SUPER is determined in compiling time
        eval "package $class;\n" . <<'EOC';
sub _hook_for_set {
    my ($self, $field) = @_;
    my $args = $self->_class_data();
    if ( exists $args->{set_hooks}{$field} ) {
        return $args->{set_hooks}{$field};
    }
    return $self->SUPER::_hook_for_set($field);
};
EOC
    }
    ## sub _hook_for_get
    if ( exists $args{get_hooks} ) {
        eval "package $class;\n" . <<'EOC';
sub _hook_for_get {
    my ($self, $field) = @_;
    my $args = $self->_class_data();
    if ( exists $args->{get_hooks}{$field} ) {
        return $args->{get_hooks}{$field};
    }
    return $self->SUPER::_hook_for_get($field);
};
EOC
    }
    ## create accessor for query fields
    my %not_allow_sub = map { $_ => 1 } qw/new get set has check query_param
                                           make_request _hook_for_get _hook_for_set/;
    for my $field ( keys %query_params ) {
        if (substr($field, 0, 1) eq '_' || exists $not_allow_sub{$field} ) {
            croak "Create not allow sub '$field'\n";
        }
        *{$class.'::'.$field} = sub {
            my $self = shift;
            if ( @_ ) {
                $self->set($field, @_);
                return $self;
            }
            return $self->get($field);
        };
    }
    ## sub _fields
    if ( exists $args{fields} ) {
        my $fields = $args{fields};
        _expand_fields($fields);
        *{$class.'::_fields'} = sub {
            my $self = shift;
            if ( @_ ) {
                return uniq( map { @{$fields->{$_}} } grep { exists $fields->{$_} } @_ );
            }
            return ();
        };
    }
    ## sub _list_paths
    *{$class.'::_list_tags'} = sub {
        return $args{list_tags};
    };
}

sub get {
    my ($self, $field) = @_;
    if ( $self->has($field) ) {
        my $hook = $self->_hook_for_get($field);
        if ( $hook ) {
            return $hook->($self, $field);
        }
        return $self->{$field};
    } else {
        ERROR("Get non exists field '$field'");
        return;
    }
}

sub set {
    my ($self, $field, $val) = @_;
    if ( $self->has($field) ) {
        my $hook = $self->_hook_for_set($field);
        if ( $hook ) {
            $self->{$field} = $hook->($self, $field, $val);
        } else {
            $self->{$field} = $val;
        }
    } else {
        ERROR("Set non exists field '$field'");
    }
    return $self;
}

sub query_param {
    my $self = shift;
    my (%param, $val);
    for my $key ( $self->_query_params ) {
        $val = $self->get($key);
        if ( defined $val ) {
            $param{$key} = $val;
        }
    }
    return %param;
}

sub _response {
    my ($self, $res) = @_;
    return Net::Top::Response->new($self, $res);
}

1;
__END__

=head1 NAME

Net::Top::Request - Base class for all request of TOP

=head1 SYNOPSIS

You should not use this module directly.

=head1 DESCRIPTION

Stub documentation for Request, 

Blah blah blah.

=head2 EXPORT

=over

=item check

检查请求是否完全。目前会检查：
  - require_params 是不是都有值
  - fields 字段是否在 :all 里

如果成功返回 1。如果失败返回 Return::Value 的 failure 对象。

=item _http_method

返回请求的方法。在 make_request 中用 http_method 设置

=item _api_method

返回 api 方法名。在 make_request 中用 api_method 设置

=item _query_params

返回 api 可选的 query 字段名，在 make_request 中用 require_params、optional_params 和 file_params 设置。

=item has

检查是否在 query_params 中的字段

=item get

得到 query 字段的值

=item set

设置 query 字段的值

=item make_request

自动生成 request 类的代码。

参数：
 * api_method
 * http_method
 * require_params
 * optional_params
 * file_params
 * fields
 * set_hooks
 * get_hooks

=over

=item api_method

调用的 api 名字，比如 taobao.item.get

=item http_method

HTTP 请求的方法，get 或者 post

=item require_params

需要

=back

=back


=head1 SEE ALSO

Mention other useful documentation such as the documentation of
related modules or operating system documentation (such as man pages
in UNIX), or any relevant external documentation such as RFCs or
standards.

If you have a mailing list set up for your module, mention it here.

If you have a web site set up for your module, mention it here.

=head1 AUTHOR

Ye Wenbin, E<lt>wenbin.ye@alibaba-inc.comE<gt>

=head1 COPYRIGHT AND LICENSE

Copyright (C) 2009 by Ye Wenbin

This program is free software; you can redistribute it and/or modify
it under the same terms as Perl itself, either Perl version 5.8.2 or,
at your option, any later version of Perl 5 you may have available.

=head1 BUGS

None reported... yet.

=cut
