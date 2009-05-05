#!/usr/bin/perl

use FindBin qw/$Bin/;
use lib "$Bin/../lib";
use Data::Dumper qw(Dumper);
use Test::More qw( no_plan );
BEGIN { use_ok( "Sim::Session" ); }

my $session = new Sim::Session;
$session->add('a');

while ( ) {
}
print Dumper($session), "\n";
