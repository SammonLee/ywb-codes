<?php
class Net_Top_Request_AreasGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'AreasGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'area_id',
                'area_type',
                'area_name',
                'parent_id',
                'zip',
            ),
        ),
        'api_type' => 'Delivery',
        'method' => 'taobao.areas.get',
        'class' => 'Net_Top_Request_AreasGet',
    )
);
