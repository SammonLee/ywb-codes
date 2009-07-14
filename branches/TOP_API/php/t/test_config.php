<?php
define('TOP_DIR', realpath(dirname(__FILE__).'/..'));
require_once('simpletest/autorun.php');

function net_top_autoload($name)
{
    $file = TOP_DIR . '/' . $name . '.class.php';
    // echo 'load ' , $file, "\n";
    if ( file_exists( $file ) ){
        require($file);
    }
}
spl_autoload_register('net_top_autoload');
