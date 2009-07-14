<?php
require_once('test_config.php');
$data_dir = dirname(__FILE__) . '/../data/';

$req = Net_Top_Request_Item::get(
    array(
        'fields' => 'iid',
        'nick' => 'me',
        'iid' => 'xxx',
        'format' => 'json'
        )
    );

$res = new Net_Top_Response(
    array( file_get_contents( $data_dir . 'item.get.json' ),
           array(
               'http_code' => 200
               )
        ),
    $req
    );
// print_r( $res->result() );

$req = Net_Top_Request_Item::get(
    array(
        'fields' => 'iid',
        'nick' => 'me',
        'iid' => 'xxx',
        )
    );
$res = new Net_Top_Response(
    array( file_get_contents( $data_dir . 'item.get.xml' ),
           array(
               'http_code' => 200
               )
        ),
    $req
    );
print_r($res->result());
