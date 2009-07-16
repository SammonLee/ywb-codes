<?php
class Net_Top_Request_ProductUpdate extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ProductUpdate',
    array(
        'parameters' => array(
            'required' => array(
                'product_id',
            ),
            'other' => array(
                'binds',
                'desc',
                'image',
                'name',
                'outer_id',
                'price',
                'sale_props',
                'tsc',
            ),
            'file' => array(
                'image',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Product',
        'method' => 'taobao.product.update',
        'class' => 'Net_Top_Request_ProductUpdate',
        'http_method' => 'post',
        'is_secure' => '1',
    )
);
