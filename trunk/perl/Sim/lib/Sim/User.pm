package Sim::User;

use strict; 
use warnings;

use Carp;
use base qw(Class::Accessor);
__PACKAGE__->mk_accessors(qw/client session/);

sub start_browsing {
    my ($self) = @_;
    my $session = $self->session;
    
    while ( my $page = $session->next ) {
        $self->client->request($page->[0]);
        $self->client->wait($page->[1]);
    }
}

sub browsing {
    my $self = shift;
    my $session = $self->session;
    if ( my $page = $session->next ) {
        $self->client->request($page->[0]);
        $self->client->wait($page->[1]);
        return 1;
    }
}

1;
__END__

=head1 NAME

Sim::User - Perl extension for blah blah blah

=head1 SYNOPSIS

   use Sim::User;
   blah blah blah

=head1 DESCRIPTION

Stub documentation for Sim::User, 

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

Ye Wenbin, E<lt>wenbinye@gmail.comE<gt>

=head1 COPYRIGHT AND LICENSE

Copyright (C) 2009 by Ye Wenbin

This program is free software; you can redistribute it and/or modify
it under the same terms as Perl itself, either Perl version 5.8.2 or,
at your option, any later version of Perl 5 you may have available.

=head1 BUGS

None reported... yet.

=cut
