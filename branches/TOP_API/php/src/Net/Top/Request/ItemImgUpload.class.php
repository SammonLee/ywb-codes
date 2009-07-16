<?php
class Net_Top_Request_ItemImgUpload extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemImgUpload',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
            ),
            'other' => array(
                'image',
                'is_major',
                'itemimg_id',
                'position',
            ),
            'file' => array(
                'image',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.img.upload',
        'class' => 'Net_Top_Request_ItemImgUpload',
        'http_method' => 'post',
        'is_secure' => '1',
    )
);
