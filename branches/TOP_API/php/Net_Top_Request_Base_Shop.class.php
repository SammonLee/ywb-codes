<?php
class Net_Top_Request_Base_Shop
{
}

class Net_Top_Request_Base_Shop_Get extends Net_Top_Request
{
    static $meta_data = array(
        'fields' => array(
            ':all' => array(
                'sid',
                'cid',
                'nick',
                'title',
                'desc',
                'bulletin',
                'pic_path',
                'created',
                'modified',
            ),
        ),
        'api_method' => 'taobao.shop.get',
        'require_params' => array(
            'fields',
            'nick',
        ),
    );
}
Net_Top_Request::cookData(Net_Top_Request_Base_Shop_Get::$meta_data);

class Net_Top_Request_Base_Shop_ShowCaseRemainCount extends Net_Top_Request
{
    static $meta_data = array(
        'api_method' => 'taobao.shop.showcase.remainCount',
        'require_params' => array(
            'session',
        ),
    );
}
Net_Top_Request::cookData(Net_Top_Request_Base_Shop_ShowCaseRemainCount::$meta_data);

class Net_Top_Request_Base_Shop_Update extends Net_Top_Request
{
    static $meta_data = array(
        'optional_params' => array(
            'title',
            'bulletin',
            'desc',
        ),
        'api_method' => 'taobao.shop.update',
        'require_params' => array(
            'session',
        ),
    );
}
Net_Top_Request::cookData(Net_Top_Request_Base_Shop_Update::$meta_data);

