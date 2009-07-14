<?php
class Net_Top_Request_Base_Product
{
}

class Net_Top_Request_Base_Product_Get extends Net_Top_Request
{
    static $meta_data = array(
        'list_tags' => array(
            'productImg',
            'productPropImg',
        ),
        'fields' => array(
            ':all' => array(
                'product_id',
                'cid',
                'cat_name',
                'name',
                'props',
                'props_str',
                'binds',
                'binds_str',
                'sale_props',
                'sale_props_str',
                'price',
                'desc',
                'pic_path',
                'productimg',
                'productpropimg',
                'created',
                'modified',
            ),
        ),
        'optional_params' => array(
            'cid',
            'props',
            'product_id',
        ),
        'api_method' => 'taobao.product.get',
        'require_params' => array(
            'fields',
        ),
    );
}
Net_Top_Request::cookData(Net_Top_Request_Base_Product_Get::$meta_data);

class Net_Top_Request_Base_Product_Search extends Net_Top_Request
{
    static $meta_data = array(
        'fields' => array(
            ':all' => array(
                'product_id',
                'cid',
                'name',
                'props',
                'price',
                'pic_path',
            ),
        ),
        'optional_params' => array(
            'q',
            'cid',
            'props',
            'page_size',
            'page_no',
        ),
        'api_method' => 'taobao.products.search',
        'require_params' => array(
            'fields',
        ),
    );
}
Net_Top_Request::cookData(Net_Top_Request_Base_Product_Search::$meta_data);

