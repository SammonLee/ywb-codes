<?php
class Net_Top_Request_ItemUpdateDelisting extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemUpdateDelisting',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.update.delisting',
        'class' => 'Net_Top_Request_ItemUpdateDelisting',
        'is_secure' => '1',
    )
);
