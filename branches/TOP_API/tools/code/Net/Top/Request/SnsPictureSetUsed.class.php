<?php
class Net_Top_Request_SnsPictureSetUsed extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SnsPictureSetUsed',
    array(
        'parameters' => array(
            'required' => array(
                'ids',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Sns',
        'method' => 'taobao.sns.picture.setUsed',
        'class' => 'Net_Top_Request_SnsPictureSetUsed',
    )
);
