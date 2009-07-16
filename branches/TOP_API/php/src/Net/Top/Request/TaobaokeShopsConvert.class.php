<?php
class Net_Top_Request_TaobaokeShopsConvert extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TaobaokeShopsConvert',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'nick',
                'sids',
            ),
            'other' => array(
                'outerCode',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'user_id',
                'shop_title',
                'click_url',
                'shop_commission',
            ),
        ),
        'api_type' => 'Taoke',
        'method' => 'taobao.taobaoke.shops.convert',
        'class' => 'Net_Top_Request_TaobaokeShopsConvert',
    )
);
