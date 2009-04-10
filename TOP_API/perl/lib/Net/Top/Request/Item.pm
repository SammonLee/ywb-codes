package Net::Top::Request::Item;
sub get {
    my $pkg = shift;
    return Net::Top::Request::Item::Get->new(@_);
}

package Net::Top::Request::Item::Get;
use base 'Net::Top::Request';

__PACKAGE__->make_request(
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
    'list_tags' => [ 'sku', 'itemimg', 'propimg' ],
    'api_method' => 'taobao.item.get',
    'require_params' => [
        'fields',
        'nick',
        'iid'
    ]
);

1;
