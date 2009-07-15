<?php
class Net_Top_Request_RefundsApplyGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'RefundsApplyGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'status',
                'seller_nick',
                'page_no',
                'page_size',
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
        'method' => 'taobao.refunds.apply.get',
        'class' => 'Net_Top_Request_RefundsApplyGet',
    )
);
