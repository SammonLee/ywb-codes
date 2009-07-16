<?php
require("test_config.php");

/* common */
$ua = new Net_Top($service_url, $app_key, $secret_key);
$req = new Net_Top_Request_Item_Get(
    array(
        'fields' => array(':all'),
        'iid' => $iid,
        'nick' => $nick
        )
    );
$res = $ua->request($ua);

/* factory */
Net_Top::setParams($service_url, $app_key, $secret_key);
$ua = Net_Top::factory();
$req = Net_Top_Request::factory(
    'ItemGet',
    array(
        'fields' => array(':all'),
        'iid' => $iid,
        'nick' => $nick
        )
    );
$res = $ua->request($ua);

/* easy */
Net_Top::setParams($service_url, $app_key, $secret_key);
$res = Net_Top::itemGet(
    array(
        'fields' => array(':all'),
        'iid' => $iid,
        'nick' => $nick
        )
    );
