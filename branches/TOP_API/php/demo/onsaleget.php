<?php
require('config.inc');

$top = new Net_Top(TOP_APPKEY, TOP_SECRET_KEY);
$req = Net_Top_Request_Item::onsaleGet(
    array(
        'fields' => array(':all'),
        'session' => 'wenbinye',
        )
    );
$res = $top->request($req);
if ( $res->isError() ) {
    echo "Something is wrong: ", $res->getMessage();
    echo $res->content();
    print_r($res->result());
}
else {
    print_r($res->result());
}

