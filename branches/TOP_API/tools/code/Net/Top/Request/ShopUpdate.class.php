<?php
class Net_Top_Request_ShopUpdate extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ShopUpdate',
    array(
        'parameters' => array(
            'optional' => array(
                'bulletin',
                'desc',
                'title',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Shop',
        'method' => 'taobao.shop.update',
        'class' => 'Net_Top_Request_ShopUpdate',
        'is_secure' => '1',
    )
);
