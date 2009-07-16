<?php
class Net_Top_Request_ItemImgDelete extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemImgDelete',
    array(
        'parameters' => array(
            'required' => array(
                'iid',
                'itemimg_id',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.item.img.delete',
        'class' => 'Net_Top_Request_ItemImgDelete',
        'is_secure' => '1',
    )
);
