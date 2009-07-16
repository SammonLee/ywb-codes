<?php
class Net_Top_Request_RefundMessagesGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'RefundMessagesGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'refund_id',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Trade',
        'method' => 'taobao.refund.messages.get',
        'class' => 'Net_Top_Request_RefundMessagesGet',
    )
);
