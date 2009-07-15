<?php
class Net_Top_Request_ProductImgUpload extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ProductImgUpload',
    array(
        'parameters' => array(
            'required' => array(
                'product_id',
                'image',
            ),
            'other' => array(
                'pic_id',
                'position',
                'is_major',
            ),
            'file' => array(
                'image',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Product',
        'method' => 'taobao.product.img.upload',
        'class' => 'Net_Top_Request_ProductImgUpload',
        'http_method' => 'post',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
