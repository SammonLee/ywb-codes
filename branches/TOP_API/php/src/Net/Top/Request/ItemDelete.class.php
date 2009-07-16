<?php
class Net_Top_Request_ItemDelete extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemDelete',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.delete',
        'class' => 'Net_Top_Request_ItemDelete',
        'is_secure' => '1',
    )
);
