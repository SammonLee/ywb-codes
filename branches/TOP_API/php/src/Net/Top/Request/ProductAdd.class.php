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
                'name',
                'price',
                'image',
            ),
            'other' => array(
                'outer_id',
                'props',
                'binds',
                'sale_props',
                'tsc',
                'customer_props',
                'desc',
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
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
