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
                'auction_point',
                'auto_repost',
                'cid',
                'desc',
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
                'location.city',
                'location.state',
                'num',
                'outer_id',
                'postage_id',
                'post_fee',
                'price',
                'property_alias',
                'props',
                'seller_cids',
                'sku _prices',
                'sku _properties',
                'sku _quantities',
                'stuff_status',
                'title',
                'valid_thru',
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
        'http_method' => 'post',
        'is_secure' => '1',
    )
);
