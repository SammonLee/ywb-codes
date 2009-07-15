<?php
class Net_Top_Request_SellercatsListUpdate extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SellercatsListUpdate',
    array(
        'parameters' => array(
            'required' => array(
                'cid',
            ),
            'other' => array(
                'name',
                'pict_url',
                'sort_order',
                'parent_cid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Shop',
        'method' => 'taobao.sellercats.list.update',
        'class' => 'Net_Top_Request_SellercatsListUpdate',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
