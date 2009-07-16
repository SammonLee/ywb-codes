<?php
class Net_Top_Request_SnsPictureAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SnsPictureAdd',
    array(
        'parameters' => array(
            'required' => array(
                'picture',
            ),
            'file' => array(
                'picture',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Sns',
        'method' => 'taobao.sns.picture.add',
        'class' => 'Net_Top_Request_SnsPictureAdd',
        'http_method' => 'post',
        'is_secure' => '1',
    )
);
