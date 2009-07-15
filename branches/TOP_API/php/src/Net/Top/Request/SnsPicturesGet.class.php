<?php
class Net_Top_Request_SnsPicturesGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SnsPicturesGet',
    array(
        'parameters' => array(
            'required' => array(
                'uid',
                'album_id',
                'start_row',
                'count',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Sns',
        'method' => 'taobao.sns.pictures.get',
        'class' => 'Net_Top_Request_SnsPicturesGet',
    )
);
