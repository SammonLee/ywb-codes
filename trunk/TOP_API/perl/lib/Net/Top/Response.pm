package Net::Top::Response;

use strict; 
use warnings;

use Carp;
use XML::Simple;
use XML::Parser;
use JSON::XS;
use Readonly;

Readonly our $SERVER_ERR => 1000;

## Don't use default parser XML::SAX::PurePerl, it has a bug for decoding
$XML::Simple::PREFERRED_PARSER = "XML::Parser";

sub new {
    my $_class = shift;
    my $class = ref $_class || $_class;
    my ($req, $res) = @_;
    my $self = bless {
        _request => $req,
        _response => $res,
        _status => 0,
        _message => '',
    }, $class;
    if ( $res->is_success ) {
        my $method = 'parse_xml';
        if ( $req->format ) {
            $method = 'parse_' . $req->format;
        }
        $self->$method();
    } else {
        $self->{_message} = $res->status_line();
    }
    return $self;
}

sub is_success {
    return shift->{_status} == 0;
}

sub message {
    return shift->{_message};
}

sub request {
    return shift->{_request};
}

sub response {
    return shift->{_response};
}

sub result {
    return shift->{_result};
}

sub parse_json {
    my $self = shift;
    my $res = $self->response;
    my $content = $res->content;
    if ( is_valid_json(\$content) ) {
        my $ref = decode_json($content);
        if ( exists $ref->{rsp} ) {
            $self->set_result( $ref->{rsp} );
        } else {
            $self->set_result( $ref );
        }
    }else {
        $self->server_error();
    }
}

sub is_valid_json {
    my $content = shift;
    return substr($$content, 0, 1) eq '{';
}

sub parse_xml {
    my $self = shift;
    my ($req, $res) = ($self->{_request}, $self->{_response});
    my $xs = XML::Simple->new();
    my $force_array_fields = [];
    if ( $req->can('_list_tags') ) {
        $force_array_fields = $req->_list_tags();
    }
    my $content = $res->content;
    if ( is_valid_xml(\$content) ) {
        $self->set_result(
            $xs->XMLin(\$content, ForceArray=> $force_array_fields, KeyAttr => [])
        );
    } else {
        $self->server_error();
    }
}

sub is_valid_xml {
    my $content = shift;
    return substr($$content, 0, 5) eq '<?xml'; 
}

sub server_error {
    my $self = shift;
    $self->set_result({
        code => $SERVER_ERR,
        msg => "Server return malformed data:\n" . $self->response->content
    });
}

sub set_result {
    my $self = shift;
    my $ref = shift;
    if ( exists $ref->{code} ) {
        $self->{_status} = $ref->{code};
        $self->{_message} = $ref->{msg};
    } else {
        $self->{_result} = $ref;
        for ( keys %$ref ) {
            $self->{$_} = $ref->{$_};
        }
    }
}

sub fields {
    my $self = shift;
    return grep { !/^_/ } keys %$self;
}

1;
__END__

=head1 NAME

Net::Top::Response - Perl extension for blah blah blah

=head1 SYNOPSIS

   use Net::Top::Response;
   blah blah blah

=head1 DESCRIPTION

Stub documentation for Net::Top::Response, 

Blah blah blah.

=head2 EXPORT

None by default.

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
