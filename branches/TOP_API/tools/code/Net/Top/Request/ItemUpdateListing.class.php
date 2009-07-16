<?php
class Net_Top_Request_ItemUpdateListing extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemUpdateListing',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
                'num',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.update.listing',
        'class' => 'Net_Top_Request_ItemUpdateListing',
        'is_secure' => '1',
    )
);
