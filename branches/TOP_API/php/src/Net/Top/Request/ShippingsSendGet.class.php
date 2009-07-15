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
                'tid',
                'buyer_nick',
                'status',
                'seller_confirm',
                'receiver_name',
                'start_created',
                'end_created',
                'freight_payer',
                'type',
                'page_no',
                'page_size',
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
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
