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
                'properties',
                'image',
            ),
            'other' => array(
                'propimg_id',
                'position',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.propimg.upload',
        'class' => 'Net_Top_Request_ItemPropimgUpload',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
        'http_method' => 'post',
    )
);
