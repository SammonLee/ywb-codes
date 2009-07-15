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
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.itemextra.add',
        'class' => 'Net_Top_Request_ItemextraAdd',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
