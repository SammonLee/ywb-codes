package Net::Top::Response;

use strict; 
use warnings;

use Carp;
use XML::Simple;
use XML::Parser;

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
        $self->parse_xml();
    } else {
        $self->{_message} = $res->status_line();
    }
    return $self;
}

sub is_success {
    my $self = shift;
    return $self->{_status} == 0;
}

sub message {
    my $self = shift;
    return $self->{_message};
}

sub parse_xml {
    my $self = shift;
    my ($req, $res) = ($self->{_request}, $self->{_response});
    my $xs = XML::Simple->new();
    my @force_array_fields;
    if ( $req->can('_fields') ) {
        @force_array_fields = $req->_fields(':array');
    }
    my $ref = $xs->XMLin($res->content, ForceArray=> \@force_array_fields, KeyAttr => []);
    if ( exists $ref->{code} ) {
        $self->{_status} = $ref->{code};
        $self->{_message} = $ref->{msg};
    } else {
        $self->{_xmlobj} = $ref;
        for ( keys %$ref ) {
            $self->{$_} = $ref->{$_};
        }
    }
}

sub result {
    my $self = shift;
    return $self->{_xmlobj};
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
