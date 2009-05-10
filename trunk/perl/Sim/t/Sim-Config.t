#!/usr/bin/perl

use FindBin qw/$Bin/;
use lib "$Bin/../lib";
use Data::Dumper qw(Dumper);
use Test::More qw( no_plan );
BEGIN { use_ok( "Sim::Config" ); }

my $url = 'http://shop6.taobao.com?itemid=46dc3a6977f0b1e89af292a93c78aa2d';
my $beacon = Sim::Config->get_beacon_by_url($url);
my $shop = Sim::Config->get_shop_by_beacon($beacon);
print Dumper($beacon, $shop), "\n";
