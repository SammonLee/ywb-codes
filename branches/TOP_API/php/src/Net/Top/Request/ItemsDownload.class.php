<?php
class Net_Top_Request_ItemsDownload extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'ItemsDownload',
    array(
        'parameters' => array(
            'optional' => array(
                'seller_cids',
                'cid',
                'q',
                'approve_status',
                'start_date',
                'end_date',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Item',
        'method' => 'taobao.items.download',
        'class' => 'Net_Top_Request_ItemsDownload',
    )
);
