<?php
class Net_Top_Request_PostagesGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'PostagesGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'postage_id',
                'name',
                'memo',
                'created',
                'modified',
                'post_price',
                'post_increase',
                'express_price',
                'express_increase',
                'ems_price',
                'ems_increase',
                'postage_mode_list',
            ),
        ),
        'api_type' => 'Item',
        'method' => 'taobao.postages.get',
        'class' => 'Net_Top_Request_PostagesGet',
        'is_secure' => '1',
    )
);
