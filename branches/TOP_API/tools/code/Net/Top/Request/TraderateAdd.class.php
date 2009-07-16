<?php
class Net_Top_Request_TraderateAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TraderateAdd',
    array(
        'parameters' => array(
            'required' => array(
                'anony',
                'content',
                'result',
                'role',
                'tid',
            ),
            'other' => array(
                'order_id',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Traderate',
        'method' => 'taobao.traderate.add',
        'class' => 'Net_Top_Request_TraderateAdd',
        'is_secure' => '1',
    )
);
