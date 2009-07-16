<?php
class Net_Top_Request_ItemSkusGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemSkusGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'iids',
                'nick',
            ),
        ),
        'list_tags' => array(
            'skus',
        ),
        'fields' => array(
            ':all' => array(
                'sku_id',
                'iid',
                'properties',
                'quantity',
                'price',
                'outer_id',
                'created',
                'modified',
                'status',
            ),
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.skus.get',
        'class' => 'Net_Top_Request_ItemSkusGet',
    )
);
