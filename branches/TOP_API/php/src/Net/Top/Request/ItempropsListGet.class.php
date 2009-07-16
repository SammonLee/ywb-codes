<?php
class Net_Top_Request_ItempropsListGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItempropsListGet',
    array(
        'parameters' => array(
            'required' => array(
                'cid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Cat',
        'method' => 'taobao.itemprops.list.get',
        'class' => 'Net_Top_Request_ItempropsListGet',
    )
);
