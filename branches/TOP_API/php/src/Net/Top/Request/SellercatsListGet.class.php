<?php
class Net_Top_Request_SellercatsListGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SellercatsListGet',
    array(
        'parameters' => array(
            'required' => array(
                'nick',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Shop',
        'method' => 'taobao.sellercats.list.get',
        'class' => 'Net_Top_Request_SellercatsListGet',
    )
);
