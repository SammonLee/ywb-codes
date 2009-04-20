<?php
class Net_Top_Request_Base_Cats
{
}

class Net_Top_Request_Base_Cats_Get extends Net_Top_Request
{
    static $meta_data = array(
        'fields' => array(
            ':all' => array(
                'cid',
                'parent_cid',
                'name',
                'is_parent',
                'status',
                'sort_order',
            ),
        ),
        'optional_params' => array(
            'parent_cid',
            'cids',
            'datetime',
        ),
        'api_method' => 'taobao.itemcats.get.v2',
        'require_params' => array(
            'fields',
        ),
    );
}
Net_Top_Request::cookData(Net_Top_Request_Base_Cats_Get::$meta_data);

class Net_Top_Request_Base_Cats_PropsGet extends Net_Top_Request
{
    static $meta_data = array(
        'fields' => array(
            ':all' => array(
                'pid',
                'name',
                'is_key_prop',
                'is_sale_prop',
                'is_color_prop',
                'is_enum_prop',
                'is_input_prop',
                'child_template',
                'must',
                'multi',
                'parent_pid',
                'parent_vid',
                'status',
                'sort_order',
            ),
        ),
        'optional_params' => array(
            'pid',
            'parent_pid',
            'is_key_prop',
            'is_sale_prop',
            'is_color_prop',
            'is_enum_prop',
            'is_input_prop',
            'datetime',
        ),
        'api_method' => 'taobao.itemprops.get.v2',
        'require_params' => array(
            'fields',
            'cid',
        ),
    );
}
Net_Top_Request::cookData(Net_Top_Request_Base_Cats_PropsGet::$meta_data);

class Net_Top_Request_Base_Cats_PropvaluesGet extends Net_Top_Request
{
    static $meta_data = array(
        'fields' => array(
            ':all' => array(
                'cid',
                'pid',
                'prop_name',
                'vid',
                'name',
                'status',
                'sort_order',
            ),
        ),
        'optional_params' => array(
            'pvs',
            'datetime',
        ),
        'api_method' => 'taobao.itempropvalues.get',
        'require_params' => array(
            'fields',
            'cid',
        ),
    );
}
Net_Top_Request::cookData(Net_Top_Request_Base_Cats_PropvaluesGet::$meta_data);

class Net_Top_Request_Base_Cats_SpuGet extends Net_Top_Request
{
    static $meta_data = array(
        'fields' => array(
            ':all' => array(
            ),
        ),
        'require_params' => array(
            'fields',
            'cid',
            'props',
        ),
    );
}
Net_Top_Request::cookData(Net_Top_Request_Base_Cats_SpuGet::$meta_data);

