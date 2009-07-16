<?php
class Net_Top_Request_ProductsSearch extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ProductsSearch',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'page_no',
                'page_size',
            ),
            'optional' => array(
                'cid',
                'props',
                'q',
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
        'method' => 'taobao.products.search',
        'class' => 'Net_Top_Request_ProductsSearch',
    )
);
