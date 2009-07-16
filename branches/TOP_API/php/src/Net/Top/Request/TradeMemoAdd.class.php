<?php
class Net_Top_Request_TradeMemoAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TradeMemoAdd',
    array(
        'parameters' => array(
            'required' => array(
                'memo',
                'tid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Trade',
        'method' => 'taobao.trade.memo.add',
        'class' => 'Net_Top_Request_TradeMemoAdd',
        'is_secure' => '1',
    )
);
