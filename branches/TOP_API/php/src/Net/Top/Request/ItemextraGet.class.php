<?php
class Net_Top_Request_ItemextraGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemextraGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'iid',
                'nick',
                'type',
            ),
        ),
        'list_tags' => array(
            'sku',
        ),
        'fields' => array(
            ':all' => array(
                'eid',
                'iid',
                'title',
                'desc',
                'feature',
                'memo',
                'type',
                'reserve_price',
                'sku',
                'created',
                'modified',
            ),
        ),
        'api_type' => 'Item',
        'method' => 'taobao.itemextra.get',
        'class' => 'Net_Top_Request_ItemextraGet',
    )
);
