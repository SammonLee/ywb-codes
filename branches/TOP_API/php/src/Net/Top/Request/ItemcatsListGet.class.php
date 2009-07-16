<?php
class Net_Top_Request_ItemcatsListGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemcatsListGet',
    array(
        'parameters' => array(
            'optional' => array(
                'cids',
                'parent_cid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Cat',
        'method' => 'taobao.itemcats.list.get',
        'class' => 'Net_Top_Request_ItemcatsListGet',
    )
);
