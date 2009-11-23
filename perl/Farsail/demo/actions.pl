#!/usr/bin/perl -w
# test.pl --- 
# Author: Ye Wenbin <wenbinye@gmail.com>
# Created: 22 Nov 2009
# Version: 0.01

use warnings;
use strict;

use FindBin qw/$Bin/;
use lib "$Bin/../lib";

use Farsail;

Farsail->createInstance(
    'actions' => "$Bin/actions.yml"
)->dispatch();

sub ACTION_hello {
    print "Hello, World\n";
}
