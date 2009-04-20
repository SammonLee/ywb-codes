<?php
class Net_Top_Request_Base_Item
{
}

class Net_Top_Request_Base_Item_Get extends Net_Top_Request
{
    static $meta_data = array(
        'list_tags' => array(
            'sku',
            'itemimg',
            'propimg',
        ),
        'fields' => array(
            ':small' => array(
                'iid',
                'title',
                'nick',
                'type',
                'cid',
                'num',
                'price',
            ),
            ':image' => array(
                'pic_path',
                'itemimg',
                'propimg',
            ),
            ':all' => array(
                ':large',
                ':image',
                'increment',
                'has_discount',
                'has_invoice',
                'has_warranty',
                'has_showcase',
                'auto_repost',
                'auction_point',
            ),
            ':large' => array(
                ':small',
                ':postage',
                'props',
                'property_alias',
                'desc',
                'seller_cids',
                'valid_thru',
                'list_time',
                'delist_time',
                'stuff_status',
                'location',
                'modified',
                'sku',
                'approve_status',
                'product_id',
            ),
            ':postage' => array(
                'post_fee',
                'express_fee',
                'ems_fee',
                'postage_id',
                'freight_payer',
            ),
        ),
        'optional_params' => array(
            'format',
        ),
        'api_method' => 'taobao.item.get',
        'require_params' => array(
            'fields',
            'nick',
            'iid',
        ),
    );
}
Net_Top_Request::cookData(Net_Top_Request_Base_Item_Get::$meta_data);

class Net_Top_Request_Base_Item_ItemsGet extends Net_Top_Request
{
    static $meta_data = array(
        'fields' => array(
            ':small' => array(
                'iid',
                'title',
                'pic_path',
                'price',
                'delist_type',
            ),
            ':all' => array(
                'iid',
                'title',
                'nick',
                'pic_path',
                'cid',
                'price',
                'type',
                'location.city',
                'delist_time',
                'post_fee',
            ),
        ),
        'optional_params' => array(
            'q',
            'start_price',
            'page_no',
            'page_size',
            'order_by',
            'nicks',
            'end_price',
            'cid',
            'format',
            'props',
            'product_id',
            'ww_status',
            'post_free',
            'location.city',
            'location.state',
        ),
        'api_method' => 'taobao.items.get',
        'require_params' => array(
            'fields',
        ),
    );
}
Net_Top_Request::cookData(Net_Top_Request_Base_Item_ItemsGet::$meta_data);

