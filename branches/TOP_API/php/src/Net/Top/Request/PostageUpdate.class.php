<?php
class Net_Top_Request_PostageUpdate extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'PostageUpdate',
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
                'postage_id',
                'postage_mode.dest',
                'Postage_mode.id',
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
        'method' => 'taobao.postage.update',
        'class' => 'Net_Top_Request_PostageUpdate',
        'is_secure' => '1',
    )
);
