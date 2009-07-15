<?php
class Net_Top_Request_SnsUserGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SnsUserGet',
    array(
        'parameters' => array(
            'required' => array(
                'uid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Sns',
        'method' => 'taobao.sns.user.get',
        'class' => 'Net_Top_Request_SnsUserGet',
    )
);
