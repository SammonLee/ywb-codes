<?php
class Net_Top_Request_ProductAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ProductAdd',
    array(
        'parameters' => array(
            'required' => array(
                'cid',
                'image',
                'name',
                'price',
            ),
            'other' => array(
                'binds',
                'customer_props',
                'desc',
                'outer_id',
                'props',
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
        'method' => 'taobao.product.add',
        'class' => 'Net_Top_Request_ProductAdd',
        'http_method' => 'post',
        'is_secure' => '1',
    )
);
