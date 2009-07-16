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
                'end_price',
                'location.city',
                'location.state',
                'order_by',
                'page_no',
                'page_size',
                'post_free',
                'start_price',
                'ww_status',
            ),
            'optional' => array(
                'cid',
                'nicks',
                'product_id',
                'props',
                'q',
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
