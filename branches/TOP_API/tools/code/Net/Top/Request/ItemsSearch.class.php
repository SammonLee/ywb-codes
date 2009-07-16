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
