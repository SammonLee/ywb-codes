<?php
class Net_Top_Request_ItemextraUpdate extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemextraUpdate',
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
                'sku_extra_ids',
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
        'method' => 'taobao.itemextra.update',
        'class' => 'Net_Top_Request_ItemextraUpdate',
        'is_secure' => '1',
    )
);
