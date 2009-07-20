<?php
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

function save_user_params ($req)
{
    $dbh = get_connection();
    list($sql, $binds) = db_select(
        'api', array('api_id', 'api_name'),
        array('api_name' => $req->getMethod())
        );
    $sth = $dbh->prepare($sql);
    $sth->execute($binds);
    $api = $sth->fetch(PDO::FETCH_ASSOC);
    $params = $req->getParameters();
    if ( isset($_SESSION['user']) ) {
        $user = find_or_create(
            'user',
            array( 'user_id' ),
            array( 'user_name' ),
            array( 'user_name' => $_SESSION['user'] )
            );
        foreach ( $params as $k => $v ) {
            update_or_create(
                'user_params',
                array('user_id', 'api_id', 'param_name'),
                array(
                    'user_id' => $user['user_id'],
                    'api_id' => $api['api_id'],
                    'param_name' => $k,
                    'param_value' => $v
                    )
                );
        }
    }
}

function get_user_params()
{
    $params = array();
    $dbh = get_connection();
    if ( isset($_SESSION['user']) ) {
        $user = find_or_create(
            'user',
            array( 'user_id' ),
            array( 'user_name' ),
            array( 'user_name' => $_SESSION['user'] )
            );
        list($sql, $binds) = db_select('user_params', null,
                  array('user_id' => $user['user_id']));
        $sth = $dbh->prepare($sql);
        $sth->execute($binds);
        while ( $row = $sth->fetch(PDO::FETCH_ASSOC) ) {
            $params[$row['api_id']][$row['param_name']] = $row['param_value'];
        }
    }
    return $params;
}

function find_or_create($table, $fields, $pk_fields, $row)
{
    $dbh = get_connection();
    $where = array();
    foreach ( $pk_fields as $n ) {
        $where[$n] = $row[$n];
    }
    list($sql, $binds) = db_select($table, $fields, $where);
    $sth = $dbh->prepare($sql);
    $sth->execute($binds);
    if ( $sth->rowCount() == 0 ) {
        list($sql2, $binds2) = db_insert($table, $row);
        $sth2 = $dbh->prepare($sql2);
        $sth2->execute($binds2);
        $sth->execute($binds);
    }
    return $sth->fetch(PDO::FETCH_ASSOC);
}

function update_or_create($table, $pk_fields, $row)
{
    $dbh = get_connection();
    $where = array();
    foreach ( $pk_fields as $n ) {
        $where[$n] = $row[$n];
    }
    list($sql, $binds) = db_select($table, array_keys($row), $where);
    $sth = $dbh->prepare($sql);
    $sth->execute($binds);
    if ( $sth->rowCount() == 0 ) {
        list($sql2, $binds2) = db_insert($table, $row);
        $sth2 = $dbh->prepare($sql2);
        $sth2->execute($binds2);
    } else {
        $dbrec = $sth->fetch(PDO::FETCH_ASSOC);
        foreach ( $dbrec as $k => $v ) {
            if ( $v != $row[$k] ) {
                list($sql, $binds) = db_update($table, $where, $row);
                $sth = $dbh->prepare($sql);
                $sth->execute($binds);
                return true;
            }
        }
    }
}

function db_select($table, $fields=null, $where=null, $order=null)
{
    if( empty($fields) ) {
        $fields = '*';
    } elseif ( is_array($fields) ){
        $fields = implode(',', $fields);
    }
    $query = '';
    $binds = array();
    if ( !empty($where) ) {
        ksort($where);
        $op = ' WHERE ';
        foreach ( $where as $k => $v ) {
            $query .= $op . $k .'=?';
            $op = ' AND ';
            $binds[] = $v;
        }
    }
    if ( !empty($order) )
        $order = ' ORDER BY ' . implode(',', $order);
    $sql = sprintf("SELECT %s FROM `%s`%s%s", $fields, $table, $query, $order);
    return array($sql, $binds);
}

function db_insert($table, $value)
{
    foreach ($value as $k => $v) { /* convert array() => '' */
        if ( is_array($v) && empty($v) ) {
            $value[$k] = '';
        }
    }
    $sql = sprintf(
        "INSERT INTO %s (%s) VALUES (%s)",
        $table, implode(',', array_keys($value)),
        make_string('?', count($value), ',')
        );
    return array($sql, array_values($value));
}

function db_update($table, $cond, $value)
{
    list($set, $b1) = binds_value($value, ',');
    list($cond, $b2) = binds_value($cond, ' AND ');
    $sql = sprintf(
        'UPDATE %s SET %s WHERE %s',
        $table, $set, $cond
        );
    return array($sql, array_merge($b1, $b2));
}

function binds_value($value, $sep = ',')
{
    $query = '';
    $binds = array();
    if ( !empty($value) ) {
        ksort($value);
        $op = '';
        foreach ( $value as $k => $v ) {
            $query .= $op . $k .'=?';
            $op = $sep;
            $binds[] = $v;
        }
    }
    return array($query, $binds);
}

function make_string($char, $num, $sep='')
{
    return implode($sep, array_fill(0, $num, $char));
}
