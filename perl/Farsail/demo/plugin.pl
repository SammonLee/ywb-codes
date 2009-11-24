#!/usr/bin/perl -w
# plugin.pl --- 
# Author: Ye Wenbin <wenbinye@gmail.com>
# Created: 23 Nov 2009
# Version: 0.01

use warnings;
use strict;

use FindBin qw/$Bin/;
use lib "$Bin/../lib";
use Farsail;

my $f = Farsail->createInstance(
    'plugins' => ['Farsail::Help'],
    'actions' => {
        'global' => {
            hello => {},
        }
    },
);
$f->addProperty('dbh', 'dbh');
$f->addMethod('system', sub { print 'system called' });
$f->system();
