<?php
require('config.inc');
$top = Net_Top::factory();
$req = Net_Top_Request::factory(
    'ItemcatsGetV2',
    array(
        'fields' => array(':all'),
        'parent_cid' => 0,
        'format' => 'xml'
        )
    );
$res = $top->request($req);
if ( $res->isError() ) {
    echo "Something is wrong: ", $res->getMessage();
}
else {
    print_r($res->result());
}

