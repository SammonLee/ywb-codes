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
    $params = get_user_params();
    while ( $row = $sth->fetch(PDO::FETCH_ASSOC) ) {
        $param = array();
        $saved_params = isset($params[$row['api_id']]) ? $params[$row['api_id']] : array();
        foreach ( array('type', 'name', 'value', 'classname', 'desc') as $n ) {
            $param[$n] = $row['param_' . $n];
        }
        if ( isset($saved_params[$param['name']]) )
            $param['value'] = $saved_params[$param['name']];
        $res[$row['api_id']][] = $param;
    }
}
elseif ( $_REQUEST['pAction'] == 'bindUser' ) {
    $user = trim($_REQUEST['user']);
    $_SESSION['user'] = $user;
    find_or_create(
            'user',
            array( 'user_id' ),
            array( 'user_name' ),
            array( 'user_name' => $user )
        );
    $res = array(
        'status' => 200,
        );
}
elseif ( $_REQUEST['pAction'] == 'unbindUser' ) {
    unset($_SESSION['user']);
    $res = array(
        'status' => 200,
        );
}
echo json_encode($res);
