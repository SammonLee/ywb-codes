<?php
class Net_Top_Request_ItemsOnsaleGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemsOnsaleGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'cid',
                'has_discount',
                'has_showcase',
                'order_by',
                'page_no',
                'page_size',
                'q',
                'seller_cids',
            ),
        ),
        'list_tags' => array(
            'items',
        ),
        'fields' => array(
            ':all' => array(
                'approve_status',
                'iid',
                'num_iid',
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
                'seller_cids',
                'outer_id',
            ),
        ),
        'api_type' => 'Item',
        'method' => 'taobao.items.onsale.get',
        'class' => 'Net_Top_Request_ItemsOnsaleGet',
        'is_secure' => '1',
    )
);
