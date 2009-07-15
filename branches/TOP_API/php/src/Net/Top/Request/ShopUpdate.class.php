<?php
class Net_Top_Request_ShopUpdate extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ShopUpdate',
    array(
        'parameters' => array(
            'optional' => array(
                'title',
                'bulletin',
                'desc',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Shop',
        'method' => 'taobao.shop.update',
        'class' => 'Net_Top_Request_ShopUpdate',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
