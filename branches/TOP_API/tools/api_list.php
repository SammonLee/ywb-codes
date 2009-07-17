<?php
require('config.inc');

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
    $dbh = get_connection();
    $sql = 'SELECT *  FROM param';
    $sth = $dbh->query($sql);
    while ( $row = $sth->fetch(PDO::FETCH_ASSOC) ) {
        $param = array();
        foreach ( array('type', 'name', 'value', 'classname', 'desc') as $n ) {
            $param[$n] = $row['param_'.$n];
        }
        $res[$row['api_id']][] = $param;
    }
}
echo json_encode($res);
