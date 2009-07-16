<?php
class Net_Top_Request_SnsPicturesGetAlbums extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SnsPicturesGetAlbums',
    array(
        'parameters' => array(
            'required' => array(
                'count',
                'start_row',
                'uid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Sns',
        'method' => 'taobao.sns.pictures.getAlbums',
        'class' => 'Net_Top_Request_SnsPicturesGetAlbums',
    )
);
