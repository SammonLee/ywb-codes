#!/usr/bin/perl

use FindBin qw/$Bin/;
use lib "$Bin/../lib";

use Test::More qw/no_plan/;
use Cwd;

use_ok("Farsail::Util", ':all');

my $cwd = getcwd;

is( expand_file('a'), $cwd .'/a');
is( expand_file('a', '/home'), '/home/a');
is( expand_file('a', 'home'), $cwd.'/home/a');


