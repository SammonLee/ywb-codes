<?php
class Net_Top_Request_ItemSkuGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemSkuGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'nick',
                'sku_id',
            ),
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
        'method' => 'taobao.item.sku.get',
        'class' => 'Net_Top_Request_ItemSkuGet',
    )
);
