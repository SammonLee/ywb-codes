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
                'title',
                'desc',
                'feature',
                'memo',
                'reserve_price',
                'sku_properties',
                'sku_quantities',
                'sku_prices',
                'sku_memos',
                'sku_ids',
                'sku_extra_ids',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.itemextra.update',
        'class' => 'Net_Top_Request_ItemextraUpdate',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
