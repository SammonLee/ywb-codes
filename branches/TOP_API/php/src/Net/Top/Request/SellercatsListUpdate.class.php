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
                'parent_cid',
                'pict_url',
                'sort_order',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Shop',
        'method' => 'taobao.sellercats.list.update',
        'class' => 'Net_Top_Request_SellercatsListUpdate',
        'is_secure' => '1',
    )
);
