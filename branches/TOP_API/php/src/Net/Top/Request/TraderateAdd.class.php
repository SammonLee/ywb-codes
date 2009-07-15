<?php
class Net_Top_Request_TraderateAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TraderateAdd',
    array(
        'parameters' => array(
            'required' => array(
                'tid',
                'content',
                'result',
                'anony',
                'role',
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
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
