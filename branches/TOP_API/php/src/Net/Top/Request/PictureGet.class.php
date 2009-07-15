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
                'picture_id',
                'picture_category_id',
                'deleted',
                'modified_time',
                'title',
                'order_by',
                'page_no',
                'page_size',
                'start_date',
                'end_date',
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
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
