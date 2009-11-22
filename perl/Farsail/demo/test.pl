#!/usr/bin/perl -w
# test.pl --- 
# Author: Ye Wenbin <wenbinye@gmail.com>
# Created: 22 Nov 2009
# Version: 0.01

use warnings;
use strict;

use FindBin qw/$Bin/;
use lib "$Bin/../lib";

package MyTest;
use base 'Farsail';

package main;
use Data::Dumper qw(Dumper);

# my $obj = MyTest->createInstance();

# my $dispatcher = new Farsail::EventDispatcher();
# $dispatcher->connect(
#     'farsail.createInstance' => sub {
#         print 'create farsail', "\n";
#     }
# );

my $f = Farsail->createInstance(
    # event_dispatcher => $dispatcher
);
# print Dumper($f), "\n";

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
