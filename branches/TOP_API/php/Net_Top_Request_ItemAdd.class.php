<?php
class Net_Top_Request_ItemAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemAdd',
    array(
        'parameters' => array(
            'required' => array(
                'num',
                'price',
                'type',
                'stuff_status',
                'title',
                'desc',
                'location.state',
                'location.city',
                'approve_status',
                'cid',
            ),
            'other' => array(
                'props',
                'freight_payer',
                'valid_thru',
                'has_invoice',
                'has_warranty',
                'auto_repost',
                'has_showcase',
                'seller_cids',
                'has_discount',
                'post_fee',
                'express_fee',
                'ems_fee',
                'list_time',
                'increment',
                'image',
                'postage_id',
                'auction_point',
                'property_alias',
                'input_pids',
                'input_str',
                'sku_properties',
                'sku_quantities',
                'sku_prices',
                'sku_outer_ids',
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
        'method' => 'taobao.item.add',
        'class' => 'Net_Top_Request_ItemAdd',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
        'http_method' => 'post',
    )
);
