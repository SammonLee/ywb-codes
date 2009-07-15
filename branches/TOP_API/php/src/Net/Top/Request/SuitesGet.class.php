<?php
class Net_Top_Request_SuitesGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'SuitesGet',
    array(
        'parameters' => array(
            'required' => array(
                'service_code',
            ),
        ),
        'fields' => array(
        ),
        'api_type' => 'Suites',
        'method' => 'taobao.suites.get',
        'class' => 'Net_Top_Request_SuitesGet',
        'is_secure' => bless( do{\(my $o = 1)}, 'JSON::XS::Boolean' ),
    )
);
