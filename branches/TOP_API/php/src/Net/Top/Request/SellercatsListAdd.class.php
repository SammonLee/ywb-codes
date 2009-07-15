<?php
class Net_Top_Request_SellercatsListAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SellercatsListAdd',
    array(
        'parameters' => array(
            'required' => array(
                'name',
            ),
            'other' => array(
                'pict_url',
                'parent_cid',
                'sort_order',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Shop',
        'method' => 'taobao.sellercats.list.add',
        'class' => 'Net_Top_Request_SellercatsListAdd',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
