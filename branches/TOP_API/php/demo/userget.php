<?php
require('config.inc');
$nick = 'tbtest1010';

$top = Net_Top::factory();
$req = Net_Top_Request::factory(
    'UserGet',
    array(
        'fields' => array(':all'),
        'nick' => $nick,
        )
    );
$res = $top->request($req);
if ( $res->isError() ) {
    echo "Something is wrong: ", $res->getMessage();
}
else {
    print_r($res->result());
}

