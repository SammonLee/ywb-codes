<?php
class Net_Top_Request_ProductPropImgDelete extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ProductPropImgDelete',
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
        'method' => 'taobao.product.propImg.delete',
        'class' => 'Net_Top_Request_ProductPropImgDelete',
        'is_secure' => '1',
    )
);
