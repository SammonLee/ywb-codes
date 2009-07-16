<?php
class Net_Top_Request_ItempropvaluesGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItempropvaluesGet',
    array(
        'parameters' => array(
            'required' => array(
                'cid',
                'fields',
            ),
            'optional' => array(
                'datetime',
                'pvs',
            ),
        ),
        'list_tags' => array(
            'prop_values',
        ),
        'fields' => array(
            ':all' => array(
                'cid',
                'pid',
                'prop_name',
                'vid',
                'name',
                'name_alias',
                'is_parent',
                'status',
                'sort_order',
            ),
        ),
        'api_type' => 'Cat',
        'method' => 'taobao.itempropvalues.get',
        'class' => 'Net_Top_Request_ItempropvaluesGet',
    )
);
