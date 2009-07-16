<?php
class Net_Top_Request_DeliverySend extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'DeliverySend',
    array(
        'parameters' => array(
            'required' => array(
                'app_ip',
                'company_code',
                'out_sid',
                'seller_address',
                'seller_area_id',
                'seller_name',
                'seller_zip',
                'tid',
            ),
            'other' => array(
                'memo',
            ),
            'optional' => array(
                'seller_mobile',
                'seller_phone',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Delivery',
        'method' => 'taobao.delivery.send',
        'class' => 'Net_Top_Request_DeliverySend',
    )
);
