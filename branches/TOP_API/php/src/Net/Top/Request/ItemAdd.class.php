<?php
class Net_Top_Request_ItemAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemAdd',
    array(
        'parameters' => array(
            'required' => array(
                'cid',
                'desc',
                'location.city',
                'location.state',
                'num',
                'price',
                'stuff_status',
                'title',
                'type',
            ),
            'other' => array(
                'approve_status',
                'auction_point',
                'auto_repost',
                'ems_fee',
                'express_fee',
                'freight_payer',
                'has_discount',
                'has_invoice',
                'has_showcase',
                'has_warranty',
                'image',
                'increment',
                'input_pids',
                'input_str',
                'lang',
                'list_time',
                'outer_id',
                'postage_id',
                'post_fee',
                'product_id',
                'property_alias',
                'props',
                'seller_cids',
                'sku_outer_ids',
                'sku_prices',
                'sku_properties',
                'sku_quantities',
                'valid_thru',
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
        'http_method' => 'post',
        'is_secure' => '1',
    )
);
