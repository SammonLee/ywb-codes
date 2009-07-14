<?php
require_once('test_config.php');

$req = Net_Top_Request_Item::get(
    array(
    'fields' => 'iid',
    'nick' => 'me',
    'iid' => 'xxx',
    'format' => 'json'
        )
    );
print_r($req->queryParams());
print_r($req->format());

$req = Net_Top_Request_Item::get(
    array(
    'fields' => 'iid',
    'nick' => 'me',
    'iid' => 'xxx',
        )
    );
print_r($req->queryParams());
