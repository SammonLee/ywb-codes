<?php
class Net_Top_Request_RefundGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'RefundGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'refund_id',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'refund_id',
                'alipay_no',
                'tid',
                'oid',
                'buyer_nick',
                'seller_nick',
                'total_fee',
                'status',
                'created',
                'refund_fee',
                'good_status',
                'has_good_return',
                'payment',
                'reason',
                'desc',
                'iid',
                'title',
                'price',
                'num',
                'good_return_time',
                'company_name',
                'sid',
                'address',
            ),
        ),
        'api_type' => 'Trade',
        'method' => 'taobao.refund.get',
        'class' => 'Net_Top_Request_RefundGet',
        'is_secure' => '1',
    )
);
