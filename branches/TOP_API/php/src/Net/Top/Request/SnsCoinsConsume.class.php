<?php
class Net_Top_Request_SnsCoinsConsume extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SnsCoinsConsume',
    array(
        'parameters' => array(
            'required' => array(
                'coin_count',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Sns',
        'method' => 'taobao.sns.coins.consume',
        'class' => 'Net_Top_Request_SnsCoinsConsume',
        'is_secure' => '1',
    )
);
