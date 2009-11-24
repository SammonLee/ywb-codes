<?php
class Net_Top_Request_TradesBoughtGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'TradesBoughtGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'end_created',
                'page_no',
                'page_size',
                'seller_nick',
                'start_created',
                'status',
                'title',
                'type',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'seller_nick',
                'buyer_nick',
                'title',
                'type',
                'created',
                'sid',
                'tid',
                'seller_rate',
                'buyer_rate',
                'status',
                'payment',
                'discount_fee',
                'adjust_fee',
                'post_fee',
                'total_fee',
                'pay_time',
                'end_time',
                'modified',
                'consign_time',
                'buyer_obtain_point_fee',
                'point_fee',
                'real_point_fee',
                'received_payment',
                'commission_fee',
                'buyer_memo',
                'seller_memo',
                'alipay_no',
                'buyer_message',
                'pic_path',
                'iid',
                'num',
                'price',
                'cod_fee',
                'shipping_type',
                'orders',
                'orders.title',
                'orders.pic_path',
                'orders.price',
                'orders.num',
                'orders.iid',
                'orders.sku_id',
                'orders.refund_status',
                'orders.status',
                'orders.tid',
                'orders.total_fee',
                'orders.payment',
                'orders.discount_fee',
                'orders.adjust_fee',
                'orders.sku_properties_name',
                'orders.item_meal_name',
            ),
        ),
        'api_type' => 'Trade',
        'method' => 'taobao.trades.bought.get',
        'class' => 'Net_Top_Request_TradesBoughtGet',
        'is_secure' => '1',
    )
);