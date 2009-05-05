#!/usr/bin/perl
use FindBin qw/$Bin/;
use lib "$Bin/../lib";
use Test::More qw( no_plan );
BEGIN { use_ok( "Sim::Server" ); }

my $server = new Sim::Server;
my $cookie = $server->gen_cookie();
print $cookie;

# ok($server->check_cookie($cookie));

