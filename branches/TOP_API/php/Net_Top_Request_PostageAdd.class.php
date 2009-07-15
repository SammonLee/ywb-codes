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
                'memo',
                'postage_mode',
                'postage_mode.type',
                'postage_mode.dest',
                'postage_mode.price',
                'postage_mode.increase',
            ),
            'optional' => array(
                'post_price',
                'post_increase',
                'express_price',
                'express_increase',
                'ems_price',
                'ems_increase',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.postage.add',
        'class' => 'Net_Top_Request_PostageAdd',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
