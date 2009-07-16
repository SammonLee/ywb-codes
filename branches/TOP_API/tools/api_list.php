<?php
$_REQUEST = array('pAction' => 'catList');

if ( $_REQUEST['pAction'] == 'catList' ) {
    $dbh = get_connection();
    $sql = 'SELECT cat_id, api_id, api_name
    FROM api join cat using(cat_id)';
    $sth = $dbh->query($sql);
    while ( $row = $sth->fetch(PDO::FETCH_ASSOC) ) {
        $res[$row['cat_id']][$row['api_id']] = $row['api_name'];
    }
}
elseif ( $_REQUEST['pAction'] == 'catProperty' ) {
    include('api_params.txt');
}

print_r($res);

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
        $dbh = new PDO($dsn, $dbconf['user'], $dbconf['pass'],
                       array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' ) );
    }
    return $dbh;
}
