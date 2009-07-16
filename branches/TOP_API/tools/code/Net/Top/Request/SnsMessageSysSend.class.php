<?php
class Net_Top_Request_SnsMessageSysSend extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SnsMessageSysSend',
    array(
        'parameters' => array(
            'required' => array(
                'content',
                'to_uid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Sns',
        'method' => 'taobao.sns.message.sysSend',
        'class' => 'Net_Top_Request_SnsMessageSysSend',
        'is_secure' => '1',
    )
);
