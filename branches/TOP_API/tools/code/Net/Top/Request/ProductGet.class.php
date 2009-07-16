<?php
class Net_Top_Request_ProductGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ProductGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'optional' => array(
                'cid',
                'product_id',
                'props',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'product_id',
                'outer_id',
                'tsc',
                'cid',
                'cat_name',
                'props',
                'props_str',
                'name',
                'binds',
                'binds_str',
                'sale_props',
                'sale_props_str',
                'price',
                'desc',
                'pic_path',
                'productimg.id',
                'productimg.url',
                'productimg.position',
                'productimg',
                'productPropImg.id',
                'productPropImg.props',
                'productPropImg.url',
                'productPropImg.position',
                'ProductPropImg',
                'created',
                'modified',
            ),
        ),
        'api_type' => 'Product',
        'method' => 'taobao.product.get',
        'class' => 'Net_Top_Request_ProductGet',
    )
);
