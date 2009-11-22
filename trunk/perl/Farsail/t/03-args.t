#!/usr/bin/perl

use FindBin qw/$Bin/;
use lib "$Bin/../lib";

use Test::More qw/no_plan/;
use Cwd;
use Path::Class;
use Log::Log4perl qw/:easy/;
use Data::Dumper qw(Dumper);

Log::Log4perl->easy_init();

use_ok("Farsail::Args");

my $args = Farsail::Args->new();
$args->getopt('pass_through', 'demo');
