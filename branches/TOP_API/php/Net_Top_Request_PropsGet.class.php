<?php
class Net_Top_Request_PropsGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'PropsGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'cid',
            ),
            'other' => array(
                'pid',
                'parent_pid',
                'is_key_prop',
                'is_sale_prop',
                'is_color_prop',
                'is_enum_prop',
                'is_input_prop',
                'is_item_prop',
                'datetime',
            ),
        ),
        'list_tags' => array(
            'item_props',
        ),
        'fields' => array(
            ':all' => array(
                'pid',
                'name',
                'is_key_prop',
                'is_sale_prop',
                'is_color_prop',
                'is_enum_prop',
                'is_input_prop',
                'is_item_prop',
                'child_template',
                'must',
                'multi',
                'parent_pid',
                'parent_vid',
                'status',
                'sort_order',
            ),
        ),
        'api_type' => 'Cat',
        'method' => 'taobao.itemprops.get.v2',
        'class' => 'Net_Top_Request_PropsGet',
    )
);
