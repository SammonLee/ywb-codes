<?php
abstract class Net_Top_Request
{
    static $factory_prefix = 'Net_Top_Request';
    static $response_class = 'Net_Top_Response';
    protected $api_name;
        
    function __construct( $args = null ) 
    {
        $part = explode('_', get_class($this));
        $this->api_name = array_pop($part);
    }

    function factory($api, $args=null)
    {
        $class = self::$factory_prefix . '_' . $api;
        return new $class($args);
    }

    function parseResponse($res) 
    {
        $class = $this->getResponseClass();
        return new $class($res, $this);
    }

    function getResponseClass()
    {
        return self::$responce_class;
    }

    function getHttpMethod()
    {
        return $this->getMetadata('http_method', 'get');
    }

    function has($field) 
    {
        return array_key_exists($field, $this->_query_params);
    }
    
    function get($name) 
    {
        if ( isset($this->_params[$name]) )
            return $this->_params[$name];
    }

    function set($name, $val) 
    {
        if ( array_key_exists($name, $this->_query_params) ) {
            $type = $this->_query_params[$name];
            if ( is_array($type) ) { // struct fields, such as location.city
                if ( is_array($val) ) {
                    foreach ( $val as $k => $v ) {
                        $k = $name.'.'.$k;
                        if ( array_key_exists($k, $this->_query_params) ) {
                            $this->_params[$k] = $v;
                        } else { // not such structed fields
                            return false;
                        }
                    }
                } else { // struct fields need an array value
                    return false;
                }
            } else {
                $this->_params[$name] = $val;
            }
        } else { // no such query fields
            return false;
        }
        return $this;
    }

    function check()
    {
    }
    
    function queryParams() 
    {
        $query = array();
        $file_params = $this->getMetaData('pa');
        $file_params = (empty($file_params) ? array() : array_flip($file_params));

            
            if ( isset($this->_params[$name]) ) {
                if ( $name == 'fields' && is_array($this->_params['fields']) ) {
                    $fields = $this->getMetaData('fields');
                    $all = array();
                    foreach ( $this->_params['fields'] as $f ) {
                        if ( substr($f, 0, 1) == ':' ) {
                            if ( array_key_exists($f, $fields) ) {
                                $all = array_merge($all, $fields[$f]);
                            }
                            else {
                                throw new Exception("Unknown field tag '{$f}'\n");
                            }
                        } else {
                            array_push($all, $f);
                        }
                    }
                    $query[$name] = implode(',', array_unique($all));
                } elseif ( array_key_exists($name, $file_params) ) {
                    $query[$name] = array($this->_params[$name]);
                }
                else {
                    $query[$name] = (string)$this->_params[$name];
                }
            }
        }
        return $query;
    }
    
    function getMetadata($fields, $default=null)
    {
        $meta = Net_Top_Metadata::get($this->api_name);
        return isset($meta[$fields]) ? $meta[$fields] : $default;
    }

    function getMethod()
    {
        return $this->getMetadata('method');
    }
    
    function getApiName()
    {
        return $this->api_name;
    }
}
