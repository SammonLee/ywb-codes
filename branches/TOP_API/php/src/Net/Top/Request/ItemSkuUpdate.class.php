<?php
class Net_Top_Request_ItemSkuUpdate extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemSkuUpdate',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
                'properties',
            ),
            'other' => array(
                'lang',
                'outer_id',
                'price',
                'quantity',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.sku.update',
        'class' => 'Net_Top_Request_ItemSkuUpdate',
        'is_secure' => '1',
    )
);
