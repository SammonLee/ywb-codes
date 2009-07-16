<?php
class Net_Top_Request_ProductImgUpload extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ProductImgUpload',
    array(
        'parameters' => array(
            'required' => array(
                'image',
                'product_id',
            ),
            'other' => array(
                'is_major',
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
        'method' => 'taobao.product.img.upload',
        'class' => 'Net_Top_Request_ProductImgUpload',
        'http_method' => 'post',
        'is_secure' => '1',
    )
);
