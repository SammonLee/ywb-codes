<?php
class Net_Top_Request_ItemsInstockGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemsInstockGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'banner',
                'order_by',
                'page_no',
                'page_size',
                'q',
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
        'method' => 'taobao.items.instock.get',
        'class' => 'Net_Top_Request_ItemsInstockGet',
        'is_secure' => '1',
    )
);
