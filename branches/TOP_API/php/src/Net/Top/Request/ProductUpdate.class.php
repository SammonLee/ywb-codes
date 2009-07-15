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
                'outer_id',
                'binds',
                'sale_props',
                'name',
                'price',
                'desc',
                'tsc',
                'image',
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
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
