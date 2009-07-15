<?php
class Net_Top_Request_ItempropListGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItempropListGet',
    array(
        'parameters' => array(
            'required' => array(
                'cid',
            ),
            'optional' => array(
                'pid',
                'child_path',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Cat',
        'method' => 'taobao.itemprop.list.get',
        'class' => 'Net_Top_Request_ItempropListGet',
    )
);
