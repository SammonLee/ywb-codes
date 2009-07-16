<?php
class Net_Top_Request_PostageAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'PostageAdd',
    array(
        'parameters' => array(
            'required' => array(
                'name',
            ),
            'other' => array(
                'ems _increase',
                'ems_price',
                'express_increase',
                'express_price',
                'memo',
                'postage_mode.dest',
                'postage_mode.increase',
                'postage_mode.price',
                'postage_mode.type',
                'post_increase',
                'post_price',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.postage.add',
        'class' => 'Net_Top_Request_PostageAdd',
        'is_secure' => '1',
    )
);
