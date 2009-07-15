<?php
class Net_Top_Request_PictureCategoryGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'PictureCategoryGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'picture_category_id',
                'picture_category_name',
                'type',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'picture_category_id',
                'picture_category_name',
                'position',
                'type',
                'total',
                'created',
                'modified',
            ),
        ),
        'api_type' => 'Media',
        'method' => 'taobao.picture.category.get',
        'class' => 'Net_Top_Request_PictureCategoryGet',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
