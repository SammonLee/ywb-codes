<?php
class Net_Top_Request_ItemUpdateShowcase extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemUpdateShowcase',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.update.showcase',
        'class' => 'Net_Top_Request_ItemUpdateShowcase',
    )
);
