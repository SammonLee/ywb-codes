<?php
class Net_Top_Request_TaobaokeReportGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TaobaokeReportGet',
    array(
        'parameters' => array(
            'required' => array(
                'date',
                'fields',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'app_key',
                'outer_code',
                'trade_id',
                'pay_time',
                'pay_price',
                'auction_id',
                'auction_title',
                'auction_number',
                'category_id',
                'category_name',
                'shop_title',
                'discount',
                'taoke_amount',
            ),
        ),
        'api_type' => 'Taoke',
        'method' => 'taobao.taobaoke.report.get',
        'class' => 'Net_Top_Request_TaobaokeReportGet',
        'is_secure' => '1',
    )
);
