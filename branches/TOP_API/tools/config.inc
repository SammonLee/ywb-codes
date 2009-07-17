<?php
define('TOP_SERVICE_URL', 'http://gw.sandbox.taobao.com/router/rest');
define('TOP_API_KEY', 'test');
define('TOP_SECRET_KEY', 'test');
define('TOP_LIBPATH', realpath(dirname(__FILE__).'/../php/src/'));
require( TOP_LIBPATH . DIRECTORY_SEPARATOR . 'Net/Top/Autoload.php');
Net_Top_Autoload::register();

function get_connection()
{
    static $dbh;
    if ( !$dbh ) {
        $dbconf = parse_ini_file(dirname(__FILE__) . '/db.ini');
        $dsn = 'mysql:';
        $sep = '';
        foreach ( array( 'dbname', 'host' ) as $n ) {
            if ( isset($dbconf[$n]) ) {
                $dsn .= $sep . $n . '=' . $dbconf[$n];
                $sep = ';';
            }
        }
// $attr = array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' );
        $dbh = new PDO($dsn, $dbconf['user'], $dbconf['pass']);
    }
    return $dbh;
}
