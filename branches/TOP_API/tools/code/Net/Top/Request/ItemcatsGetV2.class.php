<?php
class Net_Top_Request_ItemcatsGetV2 extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemcatsGetV2',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'datetime',
            ),
            'optional' => array(
                'cids',
                'parent_cid',
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
        'class' => 'Net_Top_Request_ItemcatsGetV2',
    )
);
