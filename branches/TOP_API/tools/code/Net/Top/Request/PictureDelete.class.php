<?php
class Net_Top_Request_PictureDelete extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'PictureDelete',
    array(
        'parameters' => array(
            'required' => array(
                'picture_ids',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Media',
        'method' => 'taobao.picture.delete',
        'class' => 'Net_Top_Request_PictureDelete',
        'is_secure' => '1',
    )
);
