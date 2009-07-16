<?php
class Net_Top_Request_PictureGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'PictureGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'deleted',
                'end_date',
                'modified_time',
                'order_by',
                'page_no',
                'page_size',
                'picture_category_id',
                'picture_id',
                'start_date',
                'title',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'picture_id',
                'picture_category_id',
                'picture_path',
                'title',
                'sizes',
                'pixel',
                'status',
                'deleted',
                'created',
                'modified',
            ),
        ),
        'api_type' => 'Media',
        'method' => 'taobao.picture.get',
        'class' => 'Net_Top_Request_PictureGet',
        'is_secure' => '1',
    )
);
