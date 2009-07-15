<?php
class Net_Top_Request_SnsFriendsGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SnsFriendsGet',
    array(
        'parameters' => array(
            'required' => array(
                'uid',
                'start_row',
                'count',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Sns',
        'method' => 'taobao.sns.friends.get',
        'class' => 'Net_Top_Request_SnsFriendsGet',
    )
);
