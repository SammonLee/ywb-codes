<?php
class Net_Top_Request_TraderatesGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TraderatesGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'rate_type',
                'role',
            ),
            'other' => array(
                'page_no',
                'page_size',
                'result',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'tid',
                'order_id',
                'role',
                'nick',
                'result',
                'created',
                'rated_nick',
                'item_title',
                'item_price',
                'content',
                'reply',
            ),
        ),
        'api_type' => 'Traderate',
        'method' => 'taobao.traderates.get',
        'class' => 'Net_Top_Request_TraderatesGet',
        'is_secure' => '1',
    )
);
