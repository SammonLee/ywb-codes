#!/usr/bin/perl

use FindBin qw/$Bin/;
use lib "$Bin/../lib";
use Data::Dumper qw(Dumper);
use Test::More qw( no_plan );
BEGIN { use_ok( "Sim::Config" ); }

print Sim::Config->get_pixel_by_url('http://shop6.taobao.com?itemid=2d6c4d8e18e5367368dca1f662f2b5f9');
