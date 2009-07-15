<?php
class Net_Top_Request_ProductPropimgUpload extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ProductPropimgUpload',
    array(
        'parameters' => array(
            'required' => array(
                'product_id',
                'props',
                'image',
            ),
            'other' => array(
                'pic_id',
                'position',
            ),
            'file' => array(
                'image',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Product',
        'method' => 'taobao.product.propimg.upload',
        'class' => 'Net_Top_Request_ProductPropimgUpload',
        'http_method' => 'post',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
