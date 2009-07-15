<?php
require('Json/Encoder.php');
define('META_DIR', dirname(__FILE__) . '/api_meta/');

$metadata = array();
$dh = opendir(META_DIR);
while ( $file = readdir($dh) ) {
    if ( preg_match('/\.json/', $file) && is_file(META_DIR . $file) ) {
        $api = json_decode(file_get_contents(META_DIR . $file), true);
        $metadata[$api['api_type']][$api['method']] = $api;
    }
}

echo json_encode_pretty($metadata);
