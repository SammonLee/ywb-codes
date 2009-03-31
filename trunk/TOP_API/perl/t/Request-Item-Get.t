#!/home/y/bin/perl

use FindBin qw($Bin);
use lib "$Bin/../lib";
use Test::More qw/no_plan/;
use Data::Dumper qw(Dumper);
use_ok("Net::Top::Request::Item");

my $req = Net::Top::Request::Item->get;
isa_ok($req, 'Net::Top::Request::Item::Get');
isa_ok($req, 'Net::Top::Request');

is($req->_api_method, 'taobao.item.get', '_api_method');
is($req->_http_method, 'get', '_http_method');

is_deeply([$req->_query_params],
          [ 'fields', 'nick', 'iid' ]);
is_deeply(scalar($req->_query_params),
          {
              'fields' => 1,
              'nick' => 1,
              'iid' => 1
          });

is_deeply({ $req->query_param }, { }, 'query_param');
ok(!$req->check(), 'check');

$req->fields('iid')
    ->nick('me')
    ->iid('xxx');
is_deeply({$req->query_param},
          {
              'fields' => 'iid',
              'nick' => 'me',
              'iid' => 'xxx'
          });
ok($req->check(), 'check');

$req = Net::Top::Request::Item->get(
    fields => 'iid',
    nick => 'me',
    iid => 'xxx'
);
is_deeply({$req->query_param},
          {
              'fields' => 'iid',
              'nick' => 'me',
              'iid' => 'xxx'
          });
ok($req->check(), 'check');

my %param;
$req->fields([':image']);
%param = $req->query_param();
is($param{'fields'}, 'pic_path,itemimg,propimg');

$req->fields([':image', ':small']);
%param = $req->query_param();
is($param{'fields'}, 'pic_path,itemimg,propimg,iid,title,type,cid,num,price');

$req->fields([':image', ':small', 'price', 'location']);
%param = $req->query_param();
is($param{'fields'}, 'price,location,pic_path,itemimg,propimg,iid,title,type,cid,num');

$req->fields(':image,:small,price,location');
%param = $req->query_param();
is($param{'fields'}, ':image,:small,price,location');

