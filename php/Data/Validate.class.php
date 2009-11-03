<?php
/**
 * 入口函数
 * 
 * @package 
 */
class Data_Validate
{
    public static function cmpDeeply($got, $expected)
    {
        if ( is_array($expected) ) {
            $expected = new Data_Validate_Array($expected);
        }
        if ( $expected instanceof Data_Validate_IBase ) {
        } else {
            $expected = new Data_Validate_Equal($expected);
        }
        $expected->validate($got);
    }

    public static function testDeeply($got, $expected)
    {
        try {
            self::cmpDeeply($got, $expected);
            return true;
        }
        catch( Data_Validate_Exception $e ) {
            return false;
        }
    }
}

function subhashof($arr)
{
    return new Data_Validate_Subhashof($arr);
}

function superhashof($arr)
{
    return new Data_Validate_Superhashof($arr);
}

function any()
{
    return new Data_Validate_Any(func_get_args());
}

function array_each($expected)
{
    return new Data_Validate_Array_Each($expected);
}

function number()
{
    return new Data_Validate_Number();
}

function string()
{
    return new Data_Validate_String();
}

function ignore()
{
    return new Data_Validate_Ignore();
}

/**
 * 校验类
 */
interface Data_Validate_IBase
{
    function validate($got);
}

abstract class Data_Validate_Base implements Data_Validate_IBase
{
    protected $expected;
    function __construct($expected)
    {
        $this->expected = $expected;
    }

    function validate($got)
    {
    }

    function getExpected()
    {
        return $this->expected;
    }
}

class Data_Validate_Superhashof extends Data_Validate_Base
{
    function __construct($expected)
    {
        if ( !is_array($expected) )
            throw new Data_Validate_InvalidParamException("param is not array");
        parent::__construct($expected);
    }

    function validate($got)
    {
        if ( !is_array($got) ) {
            throw new Data_Validate_InvalidException($got, $this, 'input is not array');
        }
        foreach ( $this->expected as $key => $exp ) {
            if ( !array_key_exists($key, $got) ) {
                throw new Data_Validate_KeyNotFoundException($got, $this, null, $key);
            }
            Data_Validate::cmpDeeply($got[$key], $exp);
        }
    }
}

class Data_Validate_Array extends Data_Validate_Superhashof
{
    function validate($got)
    {
        parent::validate($got);
        if ( count($this->expected) != count($got) ) {
            throw new Data_Validate_KeyNotMatchException($got, $this);
        }
    }
}

class Data_Validate_Subhashof extends Data_Validate_Base
{
    function __construct($expected)
    {
        if ( !is_array($expected) )
            throw new Data_Validate_InvalidParamException("param is not array");
        parent::__construct($expected);
    }
    
    function validate($got)
    {
        if ( !is_array($got) ) {
            throw new Data_Validate_InvalidException($got, $this, 'input is not array');
        }
        foreach ( $got as $key => $val ) {
            if ( !array_key_exists($key, $this->expected) ) {
                throw new Data_Validate_KeyNotFoundException($got, $this, null, $key);
            }
            Data_Validate::cmpDeeply($val, $this->expected[$key]);
        }
    }
}

class Data_Validate_Any extends Data_Validate_Base
{
    function __construct($expected)
    {
        if ( !is_array($expected) )
            throw new Data_Validate_InvalidParamException("param is not array");
        parent::__construct($expected);
    }

    function validate($got)
    {
        $valid = false;
        $exceptions = array();
        foreach ( $this->expected as $i => $val ) {
            try {
                Data_Validate::cmpDeeply($got, $val);
                $valid = true;
            }
            catch ( Exception $e ) {
                $exceptions[$i] = $e;
            }
        }
        if ( !$valid ) {
            throw new Data_Validate_Exception($got, $this, null, $exceptions);
        }
    }
}

class Data_Validate_Array_Each extends Data_Validate_Base
{
    function validate($got)
    {
        foreach ( $got as $val ) {
            Data_Validate::cmpDeeply($val, $this->expected);
        }
    }
}

class Data_Validate_Ignore implements Data_Validate_IBase
{
    function validate($got)
    {
    }
}

class Data_Validate_Number implements Data_Validate_IBase
{
    function validate($got)
    {
    }
}

class Data_Validate_String implements Data_Validate_IBase
{
    function validate($got)
    {
        if ( !is_string($got) ) {
            throw new Data_Validate_InvalidException($got, $this);
        }
    }
}

class Data_Validate_Equal implements Data_Validate_Base
{
    function validate($got)
    {
        if ( $got != $this->expected )
            throw new Data_Validate_Exception($got, $this, "{$got} is not equal to {$this->expected}");
    }
}

/**
 * 校验异常类
 */
class Data_Validate_InvalidParamException extends Exception
{
}

class Data_Validate_Exception extends Exception
{
    protected $err_data;
    function __construct($data, $expected, $msg=null, $err_data=null)
    {
        if ( !is_null($msg) )
            parent::__construct($msg);
        $this->data = $data;
        $this->expected = $expected;
        $this->err_data = $err_data;
    }

    function getErrorData()
    {
        return $this->err_data;
    }

    function getExpected()
    {
        return $this->expected;
    }

    function getData()
    {
        return $this->data;
    }
}

class Data_Validate_KeyNotFoundException extends Data_Validate_Exception
{
    function __toString()
    {
        return "key {$this->err_data} not found";
    }
}

class Data_Validate_InvalidException extends Data_Validate_Exception
{
    function __toString()
    {
        $type = explode('_', get_class($this->getExpected()));
        return "input is not " . implode(',', array_splice($type, 2));
    }
}

class Data_Validate_KeyNotMatchException extends Data_Validate_Exception
{
    function __toString()
    {
        $data = $this->getData();
        $expected = $this->getExpected()->getExpected();
        $unknown_keys = array_diff(array_keys($data), array_keys($expected));
        $not_found_keys = array_diff(array_keys($expected), array_keys($data));
        $msg = array();
        if ( !empty($not_found_keys) )
            $msg[] = "not found keys: " . print_r($not_found_keys, true);
        if ( !empty($unknown_keys) )
            $msg[] = "unknown keys: " . print_r($unknown_keys, true);
        return implode("\n",$msg);
    }
}
