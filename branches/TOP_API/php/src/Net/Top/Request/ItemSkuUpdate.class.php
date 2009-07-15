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
                'quantity',
                'price',
                'outer_id',
                'lang',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.sku.update',
        'class' => 'Net_Top_Request_ItemSkuUpdate',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
