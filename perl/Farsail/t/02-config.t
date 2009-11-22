#!/usr/bin/perl

use FindBin qw/$Bin/;
use lib "$Bin/../lib";

use Test::More qw/no_plan/;
use Cwd;
use Path::Class;
use Log::Log4perl qw/:easy/;
use Data::Dumper qw(Dumper);

Log::Log4perl->easy_init();

use_ok("Farsail::Config");

my $conf = {foo => 1, bar => [2]};
my $config = new Farsail::Config($conf);

isa_ok($config, 'Farsail::Config');
is($config->get('foo'), $conf->{foo});
is($config->get('bar'), $conf->{bar});
is_deeply($config->getIncludedFiles(), {});

$config = new Farsail::Config('fixtures/a.ini');
is($config->getConfigFile(), file('fixtures/a.ini')->absolute);
is($config->get('var_in_a'), 'a');
is($config->get('var_in_b'), 'b');
is_deeply(
    $config->getIncludedFiles(),
    {
        file('fixtures/b.ini')->absolute => 1,
        file('fixtures/a.ini')->absolute => 1
    }
);
