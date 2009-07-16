<?php
class Net_Top_Request_SnsFriendsAreFriends extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SnsFriendsAreFriends',
    array(
        'parameters' => array(
            'required' => array(
                'uid1',
                'uid2',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Sns',
        'method' => 'taobao.sns.friends.areFriends',
        'class' => 'Net_Top_Request_SnsFriendsAreFriends',
    )
);
