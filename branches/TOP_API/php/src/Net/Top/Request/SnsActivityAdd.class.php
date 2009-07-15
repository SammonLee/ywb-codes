<?php
class Net_Top_Request_SnsActivityAdd extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SnsActivityAdd',
    array(
        'parameters' => array(
            'required' => array(
                'content',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Sns',
        'method' => 'taobao.sns.activity.add',
        'class' => 'Net_Top_Request_SnsActivityAdd',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
