#!/usr/bin/perl -w
# itemget.pl --- 
# Author: Ye Wenbin <wenbinye@gmail.com>
# Created: 20 Apr 2009
# Version: 0.01

use warnings;
use strict;

use FindBin qw/$Bin/;
use lib "$Bin/../lib";

require TopConfig;
use Net::Top::Request::Item;
use Data::Dumper qw(Dumper);

Log::Log4perl->easy_init();

my $top = TopConfig->getClient();
my $nick = '补之';
my $iid = '72eb89f0ce9ed228dce4ecc51bcc7f8a';

my $req = Net::Top::Request::Item->get(
        fields => [':all'],
        iid => $iid,
        nick => $nick
    );
my $res = $top->request($req);
if ( $res->is_success() ) {
    print Dumper($res->result()), "\n";
} else {
    print 'Something is wrong: ', $res->message(), "\n";
}

