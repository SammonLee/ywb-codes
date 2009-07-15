<?php
class Net_Top_Request_UsersGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'UsersGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
                'nicks',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'user_id',
                'nick',
                'sex',
                'buyer_credit',
                'seller_credit',
                'created',
                'last_visit',
                'location',
                'location.city',
                'location.state',
                'location.country',
                'location.district',
            ),
        ),
        'api_type' => 'User',
        'method' => 'taobao.users.get',
        'class' => 'Net_Top_Request_UsersGet',
    )
);
