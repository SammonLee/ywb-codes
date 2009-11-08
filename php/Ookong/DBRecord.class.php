<?php
abstract class Ookong_DBRecord
{
    protected $rec;
    protected $dbrec;

    function __construct()
    {
    }
    
    function initialize($info, $dbrec=null)
    {
        $this->rec = $info;
        $this->dbrec = $dbrec;
    }
    
    function get($field, $def=null)
    {
        if ( isset($this->rec[$field]) )
            return $this->rec[$field];
        return $def;
    }

    function set($field, $val)
    {
        $this->rec[$field] = $val;
        return $this;
    }

    abstract function getDAO();
    abstract function getPkFields();

    function save()
    {
        $db = $this->getDAO();
        $pk = $this->getPkFields();
        if ( !$this->dbrec ) {
            foreach ($pk as $field) {
                if ( !isset($this->rec[$field]) ) {
                    die("{$field} is not set");
                }
            }
            $this->dbrec = $db->findOrCreate(null, $pk, $this->rec);
        }
        else {
            $changed = array();
            foreach ( $this->rec as $name => $val ) {
                if ( isset($this->dbrec[$name]) && $val != $this->dbrec[$name]) {
                    $changed[$name] = $val;
                }
            }
            if ( $changed ) {
                $this->update($changed);
            }
        }
    }

    function update($changed)
    {
        $pk = $this->getPkFields();
        $where = array();
        foreach ( $pk as $key ) {
            $where[$key] = $this->dbrec[$key];
        }
        $this->getDAO()->update($where, $changed);
        foreach ( $changed as $key => $val ) {
            $this->dbrec[$key] = $val;
        }
    }

    function delete()
    {
        $pk = $this->getPkFields();
        $where = array();
        foreach ( $pk as $key ) {
            $where[$key] = $this->dbrec[$key];
        }
        $this->getDAO()->delete($where);
        $this->initialize(array(), array());
    }
    
    static public function getByPk($pk, $class)
    {
        $row = new $class();
        $db = $row->getDAO();
        $rec = $db->selectOne(null, $pk);
        if ( !empty($rec) ) {
            $row->initialize($rec, $rec);
            return $row;
        }
        return null;
    }
}
