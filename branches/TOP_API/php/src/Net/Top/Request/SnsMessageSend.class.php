<?php
class Net_Top_Request_SnsMessageSend extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SnsMessageSend',
    array(
        'parameters' => array(
            'required' => array(
                'content',
                'id',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Sns',
        'method' => 'taobao.sns.message.send',
        'class' => 'Net_Top_Request_SnsMessageSend',
        'is_secure' => '1',
    )
);
