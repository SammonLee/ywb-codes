<?php
class Net_Top_Request_SnsMessageSysSend extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SnsMessageSysSend',
    array(
        'parameters' => array(
            'required' => array(
                'to_uid',
                'content',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Sns',
        'method' => 'taobao.sns.message.sysSend',
        'class' => 'Net_Top_Request_SnsMessageSysSend',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
