#!/usr/bin/perl -w
# demo.pl --- 
# Author: Ye Wenbin <wenbinye@gmail.com>
# Created: 22 Nov 2009
# Version: 0.01

use warnings;
use strict;

use FindBin qw/$Bin/;
use lib "$Bin/../lib";
use Data::Dumper qw(Dumper);
use Farsail;

my $f = Farsail->createInstance(
    'plugins' => ['Farsail::Help'],
    'actions' => {
        'demo' => {
            module => 'Demo',
            hello => {},
        }
    },
    args => ['help']
);
$f->dispatch();

package Demo;

sub ACTION_hello {
    print "Hello, Farsail!\n";
}

__END__

=head1 NAME

test.pl - Describe the usage of script briefly

=head1 SYNOPSIS

test.pl [options] args

      -opt --long      Option description

=head1 DESCRIPTION

Stub documentation for test.pl, 

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
