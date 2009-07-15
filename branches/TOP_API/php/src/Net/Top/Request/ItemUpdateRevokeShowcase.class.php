<?php
class Net_Top_Request_ItemUpdateRevokeShowcase extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemUpdateRevokeShowcase',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.update.revokeShowcase',
        'class' => 'Net_Top_Request_ItemUpdateRevokeShowcase',
    )
);
