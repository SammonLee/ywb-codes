package Net::Top::Request::Base::Cats::Get;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'fields' => {
    ':all' => [
      'cid',
      'parent_cid',
      'name',
      'is_parent',
      'status',
      'sort_order'
    ]
  },
  'optional_params' => [
    'parent_cid',
    'cids',
    'datetime'
  ],
  'api_method' => 'taobao.itemcats.get.v2',
  'require_params' => [
    'fields'
  ]
);

package Net::Top::Request::Base::Cats::PropsGet;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'fields' => {
    ':all' => [
      'pid',
      'name',
      'is_key_prop',
      'is_sale_prop',
      'is_color_prop',
      'is_enum_prop',
      'is_input_prop',
      'child_template',
      'must',
      'multi',
      'parent_pid',
      'parent_vid',
      'status',
      'sort_order'
    ]
  },
  'optional_params' => [
    'pid',
    'parent_pid',
    'is_key_prop',
    'is_sale_prop',
    'is_color_prop',
    'is_enum_prop',
    'is_input_prop',
    'datetime'
  ],
  'api_method' => 'taobao.itemprops.get.v2',
  'require_params' => [
    'fields',
    'cid'
  ]
);

package Net::Top::Request::Base::Cats::PropvaluesGet;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'fields' => {
    ':all' => [
      'cid',
      'pid',
      'prop_name',
      'vid',
      'name',
      'status',
      'sort_order'
    ]
  },
  'optional_params' => [
    'pvs',
    'datetime'
  ],
  'api_method' => 'taobao.itempropvalues.get',
  'require_params' => [
    'fields',
    'cid'
  ]
);

package Net::Top::Request::Base::Cats::SpuGet;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'fields' => {
    ':all' => []
  },
  'require_params' => [
    'fields',
    'cid',
    'props'
  ]
);

1;
