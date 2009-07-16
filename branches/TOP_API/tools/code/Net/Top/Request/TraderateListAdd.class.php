<?php
class Net_Top_Request_TraderateListAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TraderateListAdd',
    array(
        'parameters' => array(
            'required' => array(
                'anony',
                'content',
                'result',
                'role',
                'tid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Traderate',
        'method' => 'taobao.traderate.list.add',
        'class' => 'Net_Top_Request_TraderateListAdd',
        'is_secure' => '1',
    )
);
