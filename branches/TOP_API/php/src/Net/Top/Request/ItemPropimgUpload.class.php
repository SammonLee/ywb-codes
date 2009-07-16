<?php
class Net_Top_Request_ItemPropimgUpload extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemPropimgUpload',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
                'image',
                'properties',
            ),
            'other' => array(
                'position',
                'propimg_id',
            ),
            'file' => array(
                'image',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.propimg.upload',
        'class' => 'Net_Top_Request_ItemPropimgUpload',
        'http_method' => 'post',
        'is_secure' => '1',
    )
);
