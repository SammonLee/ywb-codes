package Net::Top::Request::Base::Shop::Get;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'fields' => {
    ':all' => [
      'sid',
      'cid',
      'nick',
      'title',
      'desc',
      'bulletin',
      'pic_path',
      'created',
      'modified'
    ]
  },
  'api_method' => 'taobao.shop.get',
  'require_params' => [
    'fields',
    'nick'
  ]
);

package Net::Top::Request::Base::Shop::ShowCaseRemainCount;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'api_method' => 'taobao.shop.showcase.remainCount',
  'require_params' => [
    'session'
  ]
);

package Net::Top::Request::Base::Shop::Update;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'optional_params' => [
    'title',
    'bulletin',
    'desc'
  ],
  'api_method' => 'taobao.shop.update',
  'require_params' => [
    'session'
  ]
);

1;
