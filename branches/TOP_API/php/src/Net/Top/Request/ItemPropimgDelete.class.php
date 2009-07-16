<?php
class Net_Top_Request_ItemPropimgDelete extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemPropimgDelete',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
                'propimg_id',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.propimg.delete',
        'class' => 'Net_Top_Request_ItemPropimgDelete',
        'is_secure' => '1',
    )
);
