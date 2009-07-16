<?php
class Net_Top_Request_ShopGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ShopGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'nick',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'sid',
                'cid',
                'title',
                'nick',
                'desc',
                'bulletin',
                'pic_path',
                'created',
                'modified',
            ),
        ),
        'api_type' => 'Shop',
        'method' => 'taobao.shop.get',
        'class' => 'Net_Top_Request_ShopGet',
    )
);
