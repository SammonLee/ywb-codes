<?php
class Net_Top_Request_ProductImgDelete extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ProductImgDelete',
    array(
        'parameters' => array(
            'required' => array(
                'pic_id',
                'product_id',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Product',
        'method' => 'taobao.product.img.delete',
        'class' => 'Net_Top_Request_ProductImgDelete',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
