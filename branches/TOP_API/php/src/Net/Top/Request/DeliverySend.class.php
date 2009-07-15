<?php
class Net_Top_Request_DeliverySend extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'DeliverySend',
    array(
        'parameters' => array(
            'required' => array(
                'tid',
                'app_ip',
                'company_code',
                'out_sid',
                'seller_name',
                'seller_area_id',
                'seller_address',
                'seller_zip',
            ),
            'other' => array(
                'memo',
            ),
            'optional' => array(
                'seller_phone',
                'seller_mobile',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Delivery',
        'method' => 'taobao.delivery.send',
        'class' => 'Net_Top_Request_DeliverySend',
    )
);
