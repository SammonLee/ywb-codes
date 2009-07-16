<?php
class Net_Top_Request_ItemSkuAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemSkuAdd',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
                'price',
                'properties',
                'quantity',
            ),
            'other' => array(
                'lang',
                'outer_id',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.sku.add',
        'class' => 'Net_Top_Request_ItemSkuAdd',
        'is_secure' => '1',
    )
);
