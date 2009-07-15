<?php
class Net_Top_Request_ItemsGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemsGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'start_price',
                'end_price',
                'page_no',
                'page_size',
                'order_by',
                'ww_status',
                'post_free',
                'location',
                'location.state',
                'location.city',
            ),
            'optional' => array(
                'q',
                'nicks',
                'cid',
                'props',
                'product_id',
            ),
        ),
        'list_tags' => array(
            'items',
        ),
        'fields' => array(
            ':all' => array(
                'iid',
                'title',
                'nick',
                'pic_path',
                'cid',
                'price',
                'type',
                'delist_time',
                'post_fee',
            ),
        ),
        'api_type' => 'Item',
        'method' => 'taobao.items.get',
        'class' => 'Net_Top_Request_ItemsGet',
    )
);
