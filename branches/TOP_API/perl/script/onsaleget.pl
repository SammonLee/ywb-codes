#!/usr/bin/perl -w
# onsaleget.pl --- 

use warnings;
use strict;

use FindBin qw/$Bin/;
use lib "$Bin/../lib";

require TopConfig;
use Net::Top::Request::Item;
use Data::Dumper qw(Dumper);

Log::Log4perl->easy_init();

my $top = TopConfig->getClient();

my $session = 'aRzmY4FFAv29AGTnrP';
my $req = Net::Top::Request::Item->onsaleGet(
        fields => [':all'],
        session => $session
    );
my $res = $top->request($req);
if ( $res->is_success() ) {
    print Dumper($res->result()), "\n";
} else {
    print 'Something is wrong: ', $res->message(), "\n";
    print $res->content, "\n";
}

