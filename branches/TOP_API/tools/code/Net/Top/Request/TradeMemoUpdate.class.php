<?php
class Net_Top_Request_TradeMemoUpdate extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TradeMemoUpdate',
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
        'method' => 'taobao.trade.memo.update',
        'class' => 'Net_Top_Request_TradeMemoUpdate',
        'is_secure' => '1',
    )
);
