<?php
class Net_Top_Request_ItemsSearch extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemsSearch',
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
        'method' => 'taobao.items.search',
        'class' => 'Net_Top_Request_ItemsSearch',
    )
);
