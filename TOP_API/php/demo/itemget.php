<?php
require('config.inc');
$iid = '72eb89f0ce9ed228dce4ecc51bcc7f8a';
$nick = '补之';

$top = new Net_Top(TOP_APPKEY, TOP_SECRET_KEY);
$req = Net_Top_Request_Item::get(
    array(
        'fields' => array(':all'),
        'iid'=> $iid,
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

