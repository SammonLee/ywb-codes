#!/usr/bin/perl -w
# hello.pl --- 
# Author: Ye Wenbin <wenbinye@gmail.com>
# Created: 22 Nov 2009
# Version: 0.01

use warnings;
use strict;

use FindBin qw/$Bin/;
use lib "$Bin/../lib";
use Farsail;
use Data::Dumper qw(Dumper);
use Log::Log4perl qw/:easy/;
use YAML;
use Data::Dumper qw(Dumper);
Log::Log4perl->easy_init();

my $f = Farsail->createInstance(%{YAML::Load(<<YML)});
plugins: ['Farsail::Help', 'Farsail::Log']
actions:
  global:
    hello:
      args:
        name: string
config: { namespace: 'demo' }
YML
$f->dispatch();

sub ACTION_hello {
    my ($self, $farsail) = @_;
    print "Hello, " . $farsail->getArgs()->get('name', 'Farsail'), "!\n";
}
1;
