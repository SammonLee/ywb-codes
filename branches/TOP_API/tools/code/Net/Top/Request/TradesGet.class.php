<?php
class Net_Top_Request_TradesGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TradesGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'iid',
                'seller_nick',
            ),
            'other' => array(
                'page_no',
                'page_size',
                'type',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'seller_nick',
                'buyer_nick',
                'title',
                'type',
                'created',
                'iid',
                'price',
                'pic_path',
                'num',
            ),
        ),
        'api_type' => 'Trade',
        'method' => 'taobao.trades.get',
        'class' => 'Net_Top_Request_TradesGet',
    )
);
