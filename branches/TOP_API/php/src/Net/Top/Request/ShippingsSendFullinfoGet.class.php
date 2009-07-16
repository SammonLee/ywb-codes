<?php
class Net_Top_Request_ShippingsSendFullinfoGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ShippingsSendFullinfoGet',
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
                'created',
                'modified',
                'status',
                'type',
                'freight_payer',
                'receiver_name',
                'receiver_mobile',
                'receiver_phone',
                'receiver_location',
            ),
        ),
        'api_type' => 'Delivery',
        'method' => 'taobao.shippings.send.fullinfo.get',
        'class' => 'Net_Top_Request_ShippingsSendFullinfoGet',
        'is_secure' => '1',
    )
);
