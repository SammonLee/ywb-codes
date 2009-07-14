package Net::Top::Request::Base::Item::Get;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'list_tags' => [
    'sku',
    'itemimg',
    'propimg'
  ],
  'fields' => {
    ':small' => [
      'iid',
      'title',
      'nick',
      'type',
      'cid',
      'num',
      'price'
    ],
    ':image' => [
      'pic_path',
      'itemimg',
      'propimg'
    ],
    ':all' => [
      ':large',
      ':image',
      'increment',
      'has_discount',
      'has_invoice',
      'has_warranty',
      'has_showcase',
      'auto_repost',
      'auction_point'
    ],
    ':large' => [
      ':small',
      ':postage',
      'props',
      'property_alias',
      'desc',
      'seller_cids',
      'valid_thru',
      'list_time',
      'delist_time',
      'stuff_status',
      'location',
      'modified',
      'sku',
      'approve_status',
      'product_id'
    ],
    ':postage' => [
      'post_fee',
      'express_fee',
      'ems_fee',
      'postage_id',
      'freight_payer'
    ]
  },
  'optional_params' => [
    'format'
  ],
  'api_method' => 'taobao.item.get',
  'require_params' => [
    'fields',
    'nick',
    'iid'
  ]
);

package Net::Top::Request::Base::Item::InstockGet;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'fields' => {
    ':small' => [
      'iid',
      'title',
      'pic_path',
      'price',
      'delist_time',
      'list_time'
    ],
    ':all' => [
      'approve_status',
      'iid',
      'title',
      'nick',
      'type',
      'cid',
      'pic_path',
      'num',
      'props',
      'valid_thru',
      'list_time',
      'price',
      'has_discount',
      'has_invoice',
      'has_warranty',
      'has_showcase',
      'modified',
      'delist_time',
      'postage_id'
    ]
  },
  'optional_params' => [
    'q',
    'page_no',
    'page_size',
    'order_by',
    'cid',
    'has_discount',
    'has_showcase',
    'seller_cids'
  ],
  'api_method' => 'taobao.items.instock.get',
  'require_params' => [
    'fields',
    'session'
  ]
);

package Net::Top::Request::Base::Item::ItemsGet;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'fields' => {
    ':small' => [
      'iid',
      'title',
      'pic_path',
      'price',
      'delist_time'
    ],
    ':all' => [
      'iid',
      'title',
      'nick',
      'pic_path',
      'cid',
      'price',
      'type',
      'location.city',
      'delist_time',
      'post_fee'
    ]
  },
  'optional_params' => [
    'q',
    'start_price',
    'page_no',
    'page_size',
    'order_by',
    'nicks',
    'end_price',
    'cid',
    'format',
    'props',
    'product_id',
    'ww_status',
    'post_free',
    'location.city',
    'location.state'
  ],
  'api_method' => 'taobao.items.get',
  'require_params' => [
    'fields'
  ]
);

package Net::Top::Request::Base::Item::OnsaleGet;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
  'fields' => {
    ':small' => [
      'iid',
      'title',
      'pic_path',
      'price',
      'delist_time',
      'list_time'
    ],
    ':all' => [
      'approve_status',
      'iid',
      'title',
      'nick',
      'type',
      'cid',
      'pic_path',
      'num',
      'props',
      'valid_thru',
      'list_time',
      'price',
      'has_discount',
      'has_invoice',
      'has_warranty',
      'has_showcase',
      'modified',
      'delist_time',
      'postage_id',
      'seller_cids'
    ]
  },
  'optional_params' => [
    'q',
    'page_no',
    'page_size',
    'order_by',
    'cid',
    'has_discount',
    'has_showcase',
    'seller_cids'
  ],
  'api_method' => 'taobao.items.onsale.get',
  'require_params' => [
    'fields',
    'session'
  ]
);

1;
