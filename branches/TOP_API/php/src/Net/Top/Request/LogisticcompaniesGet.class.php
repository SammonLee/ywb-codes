<?php
class Net_Top_Request_LogisticcompaniesGet extends Net_Top_Request
{
}

Net_Top_Metadata::add(
    'LogisticcompaniesGet',
    array(
        'parameters' => array(
            'required' => array(
                'fields',
            ),
            'other' => array(
                'is_recommended',
            ),
        ),
        'fields' => array(
            ':all' => array(
                'company_id',
                'company_code',
                'company_name',
            ),
        ),
        'api_type' => 'Delivery',
        'method' => 'taobao.logisticcompanies.get',
        'class' => 'Net_Top_Request_LogisticcompaniesGet',
    )
);
