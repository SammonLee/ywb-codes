<?php
class Net_Top_Request_TaobaokeItemsConvert extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TaobaokeItemsConvert',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'iids',
                'nick',
            ),
            'other' => array(
                'outerCode',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'iid',
                'title',
                'nick',
                'pic_url',
                'price',
                'click_url',
                'commission',
                'commission_rate',
                'commission_num',
                'commission_volume',
            ),
        ),
        'api_type' => 'Taoke',
        'method' => 'taobao.taobaoke.items.convert',
        'class' => 'Net_Top_Request_TaobaokeItemsConvert',
    )
);
