package Sim::Session;

use strict; 
use warnings;

use Carp;
use base qw(Class::Accessor);
__PACKAGE__->mk_accessors(qw/shop/);

sub add_rand_pages {
    my ($self, $num) = @_;
    my $shop = $self->shop;
    my @pages = keys %{$shop->{pages}};
    for ( 1..$num ) {
        $self->add( $pages[rand(@pages)], rand(3) );
    }
}

sub add {
    my ($self, $url, $int) = @_;
    if ( !defined $int ) {
        $int = 0;
    }
    push @{$self->{pages}}, [$url, $int];
}

sub next {
    my $self = shift;
    my $cur;
    if ( !$self->{current} ) {
        $self->{current} = 0;
    }
    if ( $self->{current} <= $#{$self->{pages}} ) {
        $cur = $self->{pages}[$self->{current}];
    }
    $self->{current}++;
    return $cur;
}

1;
__END__

=head1 NAME

Sim::Session - Perl extension for blah blah blah

=head1 SYNOPSIS

   use Sim::Session;
   blah blah blah

=head1 DESCRIPTION

Stub documentation for Sim::Session, 

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
