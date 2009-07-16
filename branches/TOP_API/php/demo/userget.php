<?php
require('config.inc');
$nick = 'alipublic01';
$session = '195602316366e90b1ccfd6319d2da46a2';
$fields = array(':all');

$top = Net_Top::factory();
$req = Net_Top_Request::factory(
    'UserGet',
    array(
        'fields' => $fields,
        'nick' => $nick,
        'session' => $session,
        )
    );
$res = $top->request($req);
print_r($res->getParameters());
if ( $res->isError() ) {
    echo "Something is wrong: ", $res->getMessage();
}
else {
    print_r($res->result());
}

