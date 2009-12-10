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

SKIP: {
skip(1);
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
}

my $res;
$config = new Farsail::Config();
# $config->define('url=s@');
$config->setConfigFile('fixtures/complex.ini');
is_deeply( $config->getSection('url', 1), ['a','b'] );
is_deeply( $config->getSection('db'), { user=>'ywb', 'pass'=>'ywb'});
is_deeply( $config->getSection('stores', 1),
           [{name=>'a', 'url'=>'a.com'},
            {name=>'b', 'url' => 'b.com'}]);

