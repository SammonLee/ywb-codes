<?php
class Net_Top_Request_CatsGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'CatsGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'datetime',
            ),
            'optional' => array(
                'parent_cid',
                'cids',
            ),
        ),
        'list_tags' => array(
            'item_cat',
        ),
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
        'api_type' => 'Cat',
        'method' => 'taobao.itemcats.get.v2',
        'class' => 'Net_Top_Request_CatsGet',
    )
);
