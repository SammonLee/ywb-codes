<?php
class Net_Top_Request_ItemsDownload extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemsDownload',
    array(
        'parameters' => array(
            'optional' => array(
                'approve_status',
                'cid',
                'end_date',
                'q',
                'seller_cids',
                'start_date',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.items.download',
        'class' => 'Net_Top_Request_ItemsDownload',
    )
);
