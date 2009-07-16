<?php
class Net_Top_Request_ShippingsSendGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ShippingsSendGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'buyer_nick',
                'end_created',
                'freight_payer',
                'page_no',
                'page_size',
                'receiver_name',
                'seller_confirm',
                'start_created',
                'status',
                'tid',
                'type',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'tid',
                'seller_nick',
                'buyer_nick',
                'delivery_start',
                'delivery_end',
                'out_sid',
                'item_title',
                'receiver_name',
                'created',
                'modified',
                'status',
                'type',
                'freight_payer',
                'seller_confirm',
                'company_name',
            ),
        ),
        'api_type' => 'Delivery',
        'method' => 'taobao.shippings.send.get',
        'class' => 'Net_Top_Request_ShippingsSendGet',
        'is_secure' => '1',
    )
);
