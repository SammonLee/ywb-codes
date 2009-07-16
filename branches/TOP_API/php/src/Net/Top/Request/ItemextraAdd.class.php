<?php
class Net_Top_Request_ItemextraAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemextraAdd',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
                'type',
            ),
            'other' => array(
                'desc',
                'feature',
                'memo',
                'reserve_price',
                'sku_ids',
                'sku_memos',
                'sku_prices',
                'sku_properties',
                'sku_quantities',
                'title',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.itemextra.add',
        'class' => 'Net_Top_Request_ItemextraAdd',
        'is_secure' => '1',
    )
);
