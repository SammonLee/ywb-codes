package Net::Top::Request::Base::Product::Get;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'list_tags' => [
    'productImg',
    'productPropImg'
  ],
  'fields' => {
    ':all' => [
      'product_id',
      'cid',
      'cat_name',
      'name',
      'props',
      'props_str',
      'binds',
      'binds_str',
      'sale_props',
      'sale_props_str',
      'price',
      'desc',
      'pic_path',
      'productimg',
      'productpropimg',
      'created',
      'modified'
    ]
  },
  'optional_params' => [
    'cid',
    'props',
    'product_id'
  ],
  'api_method' => 'taobao.product.get',
  'require_params' => [
    'fields'
  ]
);

package Net::Top::Request::Base::Product::Search;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'fields' => {
    ':all' => [
      'product_id',
      'cid',
      'name',
      'props',
      'price',
      'pic_path'
    ]
  },
  'optional_params' => [
    'q',
    'cid',
    'props',
    'page_size',
    'page_no'
  ],
  'api_method' => 'taobao.products.search',
  'require_params' => [
    'fields'
  ]
);

1;
