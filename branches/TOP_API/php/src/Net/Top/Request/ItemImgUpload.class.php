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
                'image',
            ),
            'other' => array(
                'itemimg_id',
                'position',
                'is_major',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.img.upload',
        'class' => 'Net_Top_Request_ItemImgUpload',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
        'http_method' => 'post',
    )
);
