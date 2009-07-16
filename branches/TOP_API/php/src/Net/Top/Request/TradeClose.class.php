<?php
class Net_Top_Request_TradeClose extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TradeClose',
    array(
        'parameters' => array(
            'required' => array(
                'close_reason',
                'tid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Trade',
        'method' => 'taobao.trade.close',
        'class' => 'Net_Top_Request_TradeClose',
        'is_secure' => '1',
    )
);
