<?php
class Net_Top_Request_FullskusGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'FullskusGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'outer_id',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'sku_id',
                'iid',
                'properties',
                'quantity',
                'price',
                'outer_id',
                'created',
                'modified',
                'status',
            ),
        ),
        'api_type' => 'Item',
        'method' => 'taobao.fullskus.get',
        'class' => 'Net_Top_Request_FullskusGet',
        'is_secure' => '1',
    )
);
