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
                'properties',
                'quantity',
                'price',
            ),
            'other' => array(
                'outer_id',
                'lang',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.sku.add',
        'class' => 'Net_Top_Request_ItemSkuAdd',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
