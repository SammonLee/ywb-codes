<?php
class Net_Top_Request_TaobaokeItemsGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TaobaokeItemsGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'pid',
            ),
            'other' => array(
                'area',
                'auot_send',
                'credit',
                'end_price',
                'is_guarantee',
                'page_no',
                'page_size',
                'sort',
                'strat_price',
            ),
            'optional' => array(
                'cid',
                'keyword',
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
        'api_type' => 'Item',
        'method' => 'taobao.taobaoke.items.get',
        'class' => 'Net_Top_Request_TaobaokeItemsGet',
    )
);
