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
use Log::Log4perl qw/:easy/;
Log::Log4perl->easy_init();

Farsail->createInstance(
    'plugins' => ['Farsail::Help', 'Farsail::Log'],
    'actions' => {
        'global' => {
            module => 'main',
            hello => {},
        }
    },
)->dispatch();

sub ACTION_hello {
    print "Hello, Farsail!\n";
}
