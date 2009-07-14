#!/usr/bin/perl -w
# productget.pl ---

use warnings;
use strict;

use FindBin qw/$Bin/;
use lib "$Bin/../lib";

require TopConfig;
use Net::Top::Request::Product;
use Data::Dumper qw(Dumper);

Log::Log4perl->easy_init();

my $top = TopConfig->getClient();

my $req = Net::Top::Request::Product->search(
    fields => [':all'],
    'cid' => '1512',
    'props' => '20000:10027'
    );
my $res = $top->request($req);
if ( $res->is_success() ) {
    print Dumper($res->result()), "\n";
} else {
    print 'Something is wrong: ', $res->message(), "\n";
}
