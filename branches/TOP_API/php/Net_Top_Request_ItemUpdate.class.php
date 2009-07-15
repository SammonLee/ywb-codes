<?php
class Net_Top_Request_ItemUpdate extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemUpdate',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
            ),
            'other' => array(
                'approve_status',
                'cid',
                'props',
                'num',
                'price',
                'title',
                'desc',
                'location',
                'location.state',
                'location.city',
                'freight_payer',
                'valid_thru',
                'has_invoice',
                'has_warranty',
                'auto_repost',
                'has_showcase',
                'has_discount',
                'post_fee',
                'express_fee',
                'ems_fee',
                'list_time',
                'increment',
                'image',
                'stuff_status',
                'auction_point',
                'property_alias',
                'input_pids',
                'input_str',
                'sku_quantities',
                'sku_prices',
                'sku_properties',
                'seller_cids',
                'postage_id',
                'lang',
                'outer_id',
                'product_id',
            ),
            'file' => array(
                'image',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.update',
        'class' => 'Net_Top_Request_ItemUpdate',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
        'http_method' => 'post',
    )
);
