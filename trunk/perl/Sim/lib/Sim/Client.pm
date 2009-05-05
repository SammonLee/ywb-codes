package Sim::Client;

use strict; 
use warnings;
use HTTP::Request;
require Sim::Config;
use Data::Dumper qw(Dumper);
use Carp;
use base qw(Class::Accessor);
__PACKAGE__->mk_accessors(qw/server cookie/);

sub request {
    my ($self, $url)  = @_;
    my $pixel = Sim::Config->get_pixel_by_url($url);
    my $req = HTTP::Request->new(GET => $pixel);
    $req->header('Cookie', $self->cookie);
    $req->header('Referer', $url);
    my $res = $self->server->response($req);
    $self->cookie($res->header('set-cookie'));
    return $res;
}

sub wait {
    my ($self, $interval) = @_;
    $self->server->pass($interval);
}

1;
__END__

=head1 NAME

Sim::Client - Perl extension for blah blah blah

=head1 SYNOPSIS

   use Sim::Client;
   blah blah blah

=head1 DESCRIPTION

Stub documentation for Sim::Client, 

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
