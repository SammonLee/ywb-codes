<?php
require('config.inc');
/* $iid = '72eb89f0ce9ed228dce4ecc51bcc7f8a'; */
/* $nick = '补之'; */
$iid = "efe6e4e69afe56d10c6ff1786115df91";
$nick = "tbtest1010";

function factory()
{
    $top = Net_Top::factory();
    $req = Net_Top_Request::factory(
        'ItemGet',
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
}

$top = Net_Top::factory();
$res = $top->itemGet(
    array(
        'fields' => array(':all'),
        'iid'=> $iid,
        'nick' => $nick,
        )
    );
if ( $res->isError() ) {
    echo "Something is wrong: ", $res->getMessage();
}
else {
    print_r($res->result());
}
