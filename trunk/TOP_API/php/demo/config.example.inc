<?php
define('TOP_APPKEY', '');
define('TOP_SECRET_KEY', '');
define('TOP_DIR', dirname(__FILE__).'/../');

function net_top_autoload($name)
{
    $file = TOP_DIR . $name . '.class.php';
    if ( file_exists( $file ) ){
        require($file);
    }
}
spl_autoload_register('net_top_autoload');
