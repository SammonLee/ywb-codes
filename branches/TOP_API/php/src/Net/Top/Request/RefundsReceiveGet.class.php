<?php
class Net_Top_Request_RefundsReceiveGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'RefundsReceiveGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'buyer_nick',
                'page_no',
                'page_size',
                'status',
                'type',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'refund_id',
                'tid',
                'title',
                'buyer_nick',
                'seller_nick',
                'total_fee',
                'status',
                'created',
                'refund_fee',
            ),
        ),
        'api_type' => 'Trade',
        'method' => 'taobao.refunds.receive.get',
        'class' => 'Net_Top_Request_RefundsReceiveGet',
    )
);
