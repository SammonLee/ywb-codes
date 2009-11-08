<?php
/**
 * 数据库抽象类。
 * 
 * 大部分函数都涉及多步操作，为保证所有操作都正确执行，需要以异常方式处理。
 * 所以在 setDBH 函数中强制设置 PDO::ATTR_ERRMODE
 * 为 PDO::ERRMODE_EXCEPTION。所以在使用这个类时，为保证程序的健壮性，需要
 * 捕捉异常。
 *
 * SYNOPOSIS:
 * <code>
 *  $db = new Ookong_DAO($table, $dbh);
 *  $allRows = $db->selectAll();
 *  $row = $db->selectOne(null, array('id' => $id));
 *  $db->insert($row);
 *  $db->insert($allRows);
 *  $db->update(array('id'=>$id), array('field' => $data));
 * </code>
 *
 * 调试时可以用下面的方法打开日志输出(@see Log)：
 * <code>
 *  Log::setConfig('Ookong_DAO', array('debug' => true));
 * </code>
 *
 * @copyright Copyright (c) 2009
 * @package Ookong
 * @subpackage utility
 * @author Ye Wenbin<wenbinye@gmail.com>
 */
class Ookong_DAO
{
    private $table;
    private $dbh;
    private $farm_id;
    private $readonly;
    private $fetch_type;
    
    static $logger;

    /**
     * 构造 Ookong_DAO 类。默认查询返回结果类型为 PDO::FETCH_ASSOC
     * 
     * @param string $table 表名
     * @param PDO $dbh 数据库连接
     * @param string $farm_id 用于代表数据库连接的ID，在序列化时用到
     * @param boolean $readonly 是否进行只读操作。默认值为 false。
     */
    function __construct($table=null, $dbh=null, $farm_id=0, $readonly=false )
    {
        if ( $table )
            $this->setTable($table);
        if ( $dbh )
            $this->setDBH($dbh);
        $this->setFarmId($farm_id);
        $this->setReadonly($readonly);
        $this->fetch_type = PDO::FETCH_ASSOC;
    }

    function __sleep()
    {
        return array('table', 'farm_id');
    }

    /**
     * 设置表名
     * 
     * @param $string $table 表名
     * @return Ookong_DAO 对象本身
     */
    function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * 设置数据连接
     * 
     * @param PDO $dbh 数据库连接
     * @param string $farm_id 用于代表数据库连接的ID
     * @param boolean $readonly 是否进行只读操作
     * @return Ookong_DAO 对象本身
     */
    function setDBH(PDO $dbh, $farm_id=0, $readonly=false)
    {
        $this->dbh = $dbh;
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setFarmId($farm_id);
        $this->setReadonly($readonly);
        return $this;
    }

    /**
     * 设置只读标记
     * 
     * @param boolean $readonly 是否进行只读操作
     * @return Ookong_DAO 对象本身
     */
    function setReadonly($readonly)
    {
        $this->readonly = $readonly;
        return $this;
    }

    /**
     * 设置 Farm Id
     * 
     * @param string $farm_id 用于代表数据库连接的ID
     * @return Ookong_DAO 对象本身
     */
    function setFarmId($farm_id)
    {
        $this->farm_id = $farm_id;
        return $this;
    }

    /**
     * 设置 PDO fetch 返回结果方式
     * 
     * @param int $type PDO fetch 类型常量
     * @return Ookong_DAO 对象本身
     */
    function setFetchType($type)
    {
        $this->fetch_type = $type;
        return $this;
    }
    
    /**
     * @return string 表名
     */
    function getTable()
    {
        return $this->table;
    }

    /**
     * @return string farm_id
     */
    function getFarmId()
    {
        return $this->farm_id;
    }

    /**
     * @return boolean 是否只读 
     */
    function getReadonly()
    {
        return $this->readonly;
    }

    /**
     * @return PDO 数据库连接
     */
    function getDBH()
    {
        return $this->dbh;
    }

    /**
     * @return fetch 返回类型
     */
    function getFetchType()
    {
        return $this->fetch_type;
    }
    
    private function assert($readonly=true)
    {
        if ( empty($this->table) || empty($this->dbh) )
            throw new Exception("Set table and DBH first");
        if ( !$readonly && $this->readonly ) { 
            throw new Exception("The database is readonly");
        }
    }

    /**
     * 查找表中的一条记录。如果记录不存在，则插入此记录。
     * 注意 $pk_fields 必须是表的主键或者unique key。使用例子：
     * <code>
     *  $row = $db->findOrCreate('id,name', array('name'), array('name'=>'John'));
     * </code>
     *
     * @param mixed $fields 从表中选择的字段名。@see getSelectSQL
     * @param array $pk_fields 记录 $row 中的主键名，根据 $pk_fields 从 $row 中选择数据在表中查询记录是否存在
     * @param array $row 记录。必须包含 $pk_fields 中的字段。
     * @exception Exception 如果 $table, $dbh 没有设置或 $readonly为 true
     * @exception PDOException 如果查询或插入操作失败
     * @return array 表中的记录。如果 $row 中没有包含$pk_fields中字段，返回 null
     */
    function findOrCreate($fields, $pk_fields, $row)
    {
        $this->assert(false);
        $where = array();
        foreach ( $pk_fields as $n ) {
            if ( isset($row[$n]) )
                $where[$n] = $row[$n];
            else {
                self::$logger->err("Given record didn't provide enough primary key info");
                return null;
            }
        }
        list($sql, $binds) = self::getSelectSQL($this->table, $fields, $where);
        $sth = $this->dbh->prepare($sql);
        if ( self::$logger->isDebug() ) {
            self::$logger->debug("execute " . $sql . "\nwith bind values: " . print_r($binds, true));
        }
        $res = $sth->execute($binds);
        if ( $sth->rowCount() == 0 ) {
            // the record is not in database, insert it and find it after insertion
            list($insert_sql, $insert_binds) = self::getInsertSQL($this->table, $row);
            $insert_sth = $this->dbh->prepare($insert_sql);
            if ( self::$logger->isDebug() ) {
                self::$logger->debug("execute " . $insert_sql . "\nwith bind values: " . print_r($insert_binds, true));
            }
            $res = $insert_sth->execute($insert_binds);
            $sth->execute($binds);
        }
        return $sth->fetch($this->fetch_type);
    }

    /**
     * 根据主键查找表中的一条记录，如果记录不存在，则插入此记录，
     * 如果存在，使用记录更新表。
     * 注意 $pk_fields 必须是表的主键或者unique key。使用例子：
     * <code>
     *  $row = $db->updateOrCreate(array('name'), array('name'=>'John','ab' => 'J. S.'));
     * </code>
     *
     * @param array $pk_fields 记录 $row 中的主键名，根据 $pk_fields 从 $row 中选择数据在表中查询记录是否存在
     * @param array $row 记录。必须包含 $pk_fields 中的字段。
     * @exception Exception 如果 $table, $dbh 没有设置或 $readonly为 true
     * @exception PDOException 如果查询、更新、插入操作失败
     * @return boolean 成功更新或插入记录
     */
    function updateOrCreate($pk_fields, $row)
    {
        $this->assert(false);
        $where = array();
        foreach ( $pk_fields as $n ) {
            if ( isset($row[$n]) )
                $where[$n] = $row[$n];
            else {
                self::$logger->err("Given record didn't provide enough primary key info");
                return false;
            }
        }
        list($sql, $binds) = self::getSelectSQL($this->table, array_keys($row), $where);
        $sth = $this->dbh->prepare($sql);
        if ( self::$logger->isDebug() ) {
            self::$logger->debug("execute " . $sql . "\nwith bind values: " . print_r($binds, true));
        }
        $sth->execute($binds);
        if ( $sth->rowCount() == 0 ) {
            // the record is not in database, insert it
            list($sql2, $binds2) = self::getInsertSQL($this->table, $row);
            $sth2 = $this->dbh->prepare($sql2);
            if ( self::$logger->isDebug() ) {
                self::$logger->debug("execute " . $sql2 . "\nwith bind values: " . print_r($binds2, true));
            }
            return $sth2->execute($binds2);
        } else {
            // fetch record in database, compare and update if there are differences
            $is_changed = false;
            $dbrec = $sth->fetch(PDO::FETCH_ASSOC);
            foreach ( $dbrec as $k => $v ) {
                if ( $v != $row[$k] ) {
                    $is_changed = true;
                    break;
                }
            }
            if ( $is_changed ) {
                list($sql, $binds) = self::getUpdateSQL($this->table, $where, $row);
                $sth = $this->dbh->prepare($sql);
                if ( self::$logger->isDebug() ) {
                    self::$logger->debug("execute " . $sql . "\nwith bind values: " . print_r($binds, true));
                }
                return $sth->execute($binds);
            } else {
                return true;
            }
        }
    }

    /**
     * 从表查询一条记录。参数说明参考 @see getSelectSQL
     *
     * @param mixed $fields 从表中选择的字段名
     * @param mixed $where 查询条件
     * @param mixed $order 排序方式
     * @exception Exception 如果 $table, $dbh 没有设置
     * @exception PDOException 如果查询操作失败
     * @return array 符合条件的一条记录
     */
    function selectOne($fields=null, $where=null, $order=null)
    {
        $sth = $this->select($fields, $where, $order, 1);
        return $sth->fetch($this->fetch_type);
    }

    /**
     * 从表查询所有记录。参数说明参考 @see getSelectSQL
     * 
     * @param mixed $fields 从表中选择的字段名
     * @param mixed $where 查询条件
     * @param mixed $order 排序方式
     * @param mixed $limit 限制条数
     * @exception Exception 如果 $table, $dbh 没有设置
     * @exception PDOException 如果查询操作失败
     * @return array 符合条件的所有记录
     */
    function selectAll($fields=null, $where=null, $order=null, $limit=null)
    {
        $sth = $this->select($fields, $where, $order, $limit);
        return $sth->fetchAll($this->fetch_type);
    }

    /**
     * 从表查询记录。参数说明参考 @see getSelectSQL
     * 如果需要灵活调用 fetch 函数，可以使用此函数
     * 
     * @param mixed $fields 从表中选择的字段名
     * @param mixed $where 查询条件
     * @param mixed $order 排序方式
     * @param mixed $limit 限制条数
     * @exception Exception 如果 $table, $dbh 没有设置
     * @exception PDOException 如果查询操作失败
     * @return PDOStatement 执行查询后的 PDOStatement 对象
     */
    function select($fields=null, $where=null, $order=null, $limit=null)
    {
        $this->assert();
        list($sql, $binds) = self::getSelectSQL($this->table, $fields, $where, $order, $limit);
        $sth = $this->dbh->prepare($sql);
        if ( self::$logger->isDebug() ) {
            self::$logger->debug("execute " . $sql . "\nwith bind values: " . print_r($binds, true));
        }
        $sth->execute($binds);
        return $sth;
    }

    /**
     * 插入一条或多条记录到表中。
     * 当插入多条记录时，尽量保证所有记录的字段一致。
     * 如果第2条记录之后的记录字段不与第1条记录字段一致，
     * 缺少的字段将使用 null 代替，多余的字段将忽略
     * 
     * @param array $rows 如果是关联数组，将作为一条记录插入表中。如果否则将作为多条记录处理。
     * @exception Exception 如果 $table, $dbh 没有设置或 $readonly为 true
     * @exception PDOException 如果插入操作失败
     * @return boolean 如果成功插入记录
     */
    function insert($rows)
    {
        $this->assert(false);
        if ( empty($rows) )
            return false;
        // rows is in fact on record, normalize to an array of array
        if ( !(isset($rows[0]) && is_array($rows[0])) )
            $rows = array($rows);
        $row = $rows[0];
        list($sql, $binds) = self::getInsertSQL($this->table, $row);
        if ( empty($sql) )
            return false;
        $fields = array_keys($row);
        $sth = $this->dbh->prepare($sql);
        foreach ( $rows as $row ) {
            $value = array();
            foreach ( $fields as $key ) {
                $value[] = isset($row[$key]) ? $row[$key] : null;
            }
            if ( self::$logger->isDebug() ) {
                self::$logger->debug("execute " . $sql . "\nwith bind values: " . print_r($value, true));
            }
            $sth->execute($value);
        }
        return true;
    }

    /**
     * 更新数据库表。参数参考 @see getUpdateSQL
     *
     * @param mixed $where 设置更新查询条件
     * @param array $set 更新内容
     * @exception Exception 如果 $table, $dbh 没有设置或 $readonly为 true
     * @exception PDOException 如果更新操作失败
     * @return bool 如果成功更新
     */
    function update($where, $set)
    {
        $this->assert(false);
        list($sql, $binds) = self::getUpdateSQL($this->table, $where, $set);
        if ( empty($sql) )
            return false;
        $sth = $this->dbh->prepare($sql);
        if ( self::$logger->isDebug() ) {
            self::$logger->debug("execute " . $sql . "\nwith bind values: " . print_r($binds, true));
        }
        return $sth->execute($binds);
    }

    /**
     * 删除记录
     *
     * @param mixed $where 设置删除查询条件。如果确实需要删除所有记录，可设置为 null
     * @exception Exception 如果 $table, $dbh 没有设置或 $readonly为 true
     * @exception PDOException 如果删除操作失败
     * @return bool 如果成功删除
     */
    function delete($where)
    {
        $this->assert(false);
        list($sql, $binds) = self::getDeleteSQL($this->table, $where);
        $sth = $this->dbh->prepare($sql);
        if ( self::$logger->isDebug() ) {
            self::$logger->debug("execute " . $sql . "\nwith bind values: " . print_r($binds, true));
        }
        return $sth->execute($binds);
    }

    /**
     * 直接执行 $sql 语句，等价于 $db->getDBH()->query($sql)
     * 
     * @param string $sql
     * @exception PDOException 如果操作失败
     * @return PDOStatement 执行查询后的 PDOStatement 对象
     */
    function query($sql)
    {
        $this->assert();
        if ( self::$logger->isDebug() ) {
            self::$logger->debug("query " . $sql);
        }
        return $this->dbh->query($sql);
    }

    /**
     * 直接执行 $sql 语句，等价于 $db->getDBH()->exec($sql)
     * 
     * @param string $sql
     * @exception PDOException 如果操作失败
     * @return boolean 如果 SQL 执行成功
     */
    function exec($sql)
    {
        $this->assert();
        if ( self::$logger->isDebug() ) {
            self::$logger->debug("execute " . $sql);
        }
        return $this->dbh->exec($sql);
    }

    /**
     * 锁定数据库表
     * 
     * @param string $table 需要锁定的数据库表，默认为 $db->table
     * @param string $type 默认为 READ 锁定
     * @return bool 如果操作成功
     */
    function lockTable($table=null, $type="READ")
    {
        if ( !empty($table) )
            $table = $this->table;
        return $this->exec("LOCK TABLES " . $table . " " . $type);
    }

    /**
     * 解除数据库锁定
     * 
     * @return bool 如果操作成功
     */
    function unlockTable()
    {
        return $this->exec("UNLOCK TABLES");
    }

    /**
     * 产生 select SQL 语句。大致产生的 SQL 结构为：
     *  SELECT $fields FROM $table WHERE $where ORDER BY $order LIMIT $limit
     * 使用示例：
     * <code>
     *  list($sql, $binds) = Ookong_DAO::getSelectSQL(
     *      'user', array('id', 'name', 'ab'),
     *      array('id' => 1, 'name'=>'John'),
     *      array('name')
     *    );
     *  // $sql = SELECT id,name,ab FROM user WHERE id=? AND name=? ORDER BY name
     *  // $binds = array(1, 'John');
     *  list ($sql, $binds) = Ookong_DAO::getSelectSQL(
     *     'user join profile using(id)', 'id,email',
     *     array('name like ?', array('John%')), 'name ASC'
     *  );
     *  // $sql = SELECT id,email FROM user join profile using(id) WHERE name like ? ORDER BY name ASC
     *  // $binds = array('John%')
     * </code>
     * 
     * @param string $table select SQL中的表名，不做引号转义
     * @param mixed $fields 选择的字段名。值可分为以下情况：
     * <ul>
     *  <li>如果为 null，替换为 '*'。
     *  <li>如果为字符串，直接使用这个字符串
     *  <li>如果为数组，使用 ',' 连接
     * </ul>
     * @param mixed $where 查询条件。参考 @see getWhereClause
     * @param mixed $order 排序语句。值可以是字符串，直接作排序语句，或者是数组，将使用 ',' 连接
     * @param mixed $limit LIMIT 语句。值可以是字符串或整数，直接作为 limit 语句，或者是数组，生成 LIMIT $limit[0] OFFSET $limit[1]
     * @return array 第一个元素是 SQL 语句，第二个元素是绑定参数
     */
    public static function getSelectSQL($table, $fields=null, $where=null, $order=null, $limit=null)
    {
        if( empty($fields) ) {
            $fields = '*';
        } elseif ( is_array($fields) ){
            $fields = implode(',', $fields);
        }
        $whereClause = '';
        $limitClause = '';
        $orderClause = '';
        $binds = array();
        list($whereClause, $binds) = self::getWhereClause($where);
        if ( !empty($order) ) {
            if ( is_array($order) ) {
                $orderClause = ' ORDER BY ' . implode(',', $order);
            } else {
                $orderClause = ' ORDER BY ' . $order;
            }
        }
        if ( !empty($limit) ) {
            if ( is_array($limit) ) {
                $limitClause = ' LIMIT ' . (int) $limit[0];
                if ( isset($limit[1]) )
                    $limitClause .= ' OFFSET ' . (int) $limit[1];
            }
            else {
                $limitClause = ' LIMIT ' . (int) $limit;
            }
        }
        $sql = sprintf("SELECT %s FROM %s%s%s%s", $fields, $table, $whereClause, $orderClause, $limitClause);
        return array($sql, $binds);
    }

    /**
     * 产生 insert SQL 语句
     * 
     * @param string $table insert SQL中的表名，不做引号转义
     * @param array $value 关联数组记录
     * @return array 第一个元素是 SQL 语句，第二个元素是绑定参数。如果 $value 为空，返回 array(null, null)
     */
    public static function getInsertSQL($table, $value)
    {
        if ( empty($value) )
            return array(null, null);
        foreach ($value as $k => $v) { /* convert array() => '' */
            if ( is_array($v) && empty($v) ) {
                $value[$k] = '';
            }
        }
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table, implode(',', array_keys($value)),
            self::makeString('?', count($value), ',')
            );
        return array($sql, array_values($value));
    }

    /**
     * 产生 update SQL 语句
     * 
     * @param string $table update SQL中的表名，不做引号转义
     * @param mixed $where WHERE 语句。参考 @see getWhereClause
     * @param array $set 如果是关联数组，生成 key=value 的 SET 语句。如果
     * @return array 第一个元素是 SQL 语句，第二个元素是绑定参数。如果 $set 为空，返回array(null, null)
     */
    public static function getUpdateSQL($table, $where, $set)
    {
        if ( empty($set) ) {
            return array(null, null);
        }
        $setClause = '';
        $binds = array();
        if ( is_array($set) ) {
            if ( isset($set[0]) ) {
                $setClause = $set[0];
                if ( isset($set[1]) ) {
                    if ( is_array($set[1]) )
                        $binds = $set[1];
                    else
                        $binds = array($set[1]);
                }
            }
            else {
                ksort($set);
                $sep = '';
                foreach ( $set as $k => $v ) {
                    $setClause .= $sep . $k .'=?';
                    $sep = ', ';
                    $binds[] = $v;
                }
            }
        }
        else {
            $setClause = $set;
        }
        list($whereClause, $whereBinds) = self::getWhereClause($where);
        $binds = array_merge($binds, $whereBinds);
        $sql = sprintf(
            'UPDATE %s SET %s%s',
            $table, $setClause, $whereClause
            );
        return array($sql, $binds);
    }

    /**
     * 产生 delete SQL 语句
     * 
     * @param string $table delete SQL中的表名，不做引号转义
     * @param mixed $where WHERE 语句。参考 @see getWhereClause
     * @return array 第一个元素是 SQL 语句，第二个元素是绑定参数
     */
    public static function getDeleteSQL($table, $where)
    {
        list($whereClause, $binds) = self::getWhereClause($where);
        $sql = sprintf("DELETE FROM %s%s", $table, $whereClause);
        return array($sql, $binds);
    }

    /**
     * 产生 where 语句
     * 
     * @param mixed $where 值可分为以下情况：
     * <ul>
     *  <li>如果为 null，不设置 where 条件
     *  <li>如果为关联数组，设置为 AND 连接的 key=value 查询条件
     *  <li>如果只有两个元素的数组，第一个元素必须是字符串，将直接作为 where 查询语句; 第二个元素必须是一个数组，作为绑定参数
     *  <li>如果为字符串，直接作为 where 查询语句
     * </ul>
     * @return array 第一个元素是 where SQL 语句，第二个元素是绑定参数
     */
    public static function getWhereClause($where)
    {
        $query = '';
        $binds = array();
        if ( !empty($where) ) {
            if ( is_array($where) ) {
                if ( isset($where[0]) ) {
                    $query = $where[0];
                    if ( isset($where[1]) ) {
                        if ( is_array($where[1]) ) {
                            $binds = $where[1];
                        } else {
                            $binds = array($where[1]);
                        }
                    }
                }
                else {
                    ksort($where);
                    $op = '';
                    foreach ( $where as $k => $v ) {
                        $query .= $op . $k .'=?';
                        $op = ' AND ';
                        $binds[] = $v;
                    }
                }
            } else {
                $query = $where;
            }
        }
        if ( !empty($query) )
            $query = ' WHERE ' . $query;
        return array($query, $binds);
    }

    /**
     * 将字符串使用分隔符拼接的重复字符串
     * 
     * @param string $char 需要重复的字符串
     * @param int $num 重复次数
     * @param string $sep 分离符
     * @return string 
     */
    public static function makeString($char, $num, $sep='')
    {
        return implode($sep, array_fill(0, $num, $char));
    }
}
Ookong_DAO::$logger = Ookong_Log::getLogger('Ookong_DAO');
