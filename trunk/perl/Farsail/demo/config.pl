#!/usr/bin/perl -w
# config.pl --- 
# Author: Ye Wenbin <wenbinye@gmail.com>
# Created: 23 Nov 2009
# Version: 0.01

use warnings;
use strict;

use FindBin qw/$Bin/;
use lib "$Bin/../lib";

use Farsail;

Farsail->createInstance(
    'config' => "$Bin/farsail.ini"
)->dispatch();

sub ACTION_hello {
    print "Hello, World\n";
}
