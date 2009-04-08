<?php
class Net_Top_Request_Item
{
    static function get ( $args = null ) {
        return new Net_Top_Request_Item_Get($args);
    }
}

class Net_Top_Request_Item_Get extends Net_Top_Request
{
    static $meta_data = array(
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
Net_Top_Request::cookData(Net_Top_Request_Item_Get::$meta_data);
