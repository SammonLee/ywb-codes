<?php
class Net_Top_Request_PictureUpload extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'PictureUpload',
    array(
        'parameters' => array(
            'required' => array(
                'image_input_title',
                'img',
                'picture_category_id',
            ),
            'other' => array(
                'title',
            ),
            'file' => array(
                'img',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Media',
        'method' => 'taobao.picture.upload',
        'class' => 'Net_Top_Request_PictureUpload',
        'http_method' => 'post',
        'is_secure' => '1',
    )
);
