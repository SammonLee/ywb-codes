<?php
class Net_Top_Request_TaobaokeListurlGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TaobaokeListurlGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'nick',
                'q',
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
        'method' => 'taobao.taobaoke.listurl.get',
        'class' => 'Net_Top_Request_TaobaokeListurlGet',
    )
);
