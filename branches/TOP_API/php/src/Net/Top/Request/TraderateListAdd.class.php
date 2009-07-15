<?php
class Net_Top_Request_TraderateListAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TraderateListAdd',
    array(
        'parameters' => array(
            'required' => array(
                'tid',
                'content',
                'result',
                'anony',
                'role',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Traderate',
        'method' => 'taobao.traderate.list.add',
        'class' => 'Net_Top_Request_TraderateListAdd',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
