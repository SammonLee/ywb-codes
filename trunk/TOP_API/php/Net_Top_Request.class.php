<?php
abstract class Net_Top_Request
{
    static $meta_data = array(
        'http_method' => 'get',
        );
    protected $_check_error;
    protected $_query_params;
    protected $_params;

    function __construct( $args = null ) 
    {
        # accelerate for set
        $this->_query_params = $this->getMetaData('query_params');
        if ( !empty($args) ) {
            foreach ( $args as $k => $v ) {
                $this->set($k, $v);
            }
        }
    }
    
    function getMetaData($name, $all=FALSE)
    {
        $class = get_class($this);
        if ( $all ) {
            $values = array();
            while ($class) {
                $vars = get_class_vars($class);
                if ( isset($vars['meta_data'][$name]) ) {
                    array_push($values, $vars['meta_data'][$name]);
                }
                $class = get_parent_class($class);
            }
            return $values;
        }
        else {
            while ( $class ) {
                $vars = get_class_vars($class);
                if ( isset($vars['meta_data'][$name]) ) {
                    return $vars['meta_data'][$name];
                }
                $class = get_parent_class($class);
            }
        }
    }
    
    function apiMethod () 
    {
        return $this->getMetaData('api_method');
    }
    
    function httpMethod() 
    {
        return $this->getMetaData('http_method');
    }

    function check()
    {
        $require_params = $this->getMetaData('require_params');
        if ( $require_params ) {
            foreach ( $require_params as $name ) {
                if ( !isset($this->_params[$name]) ) {
                    $this->_check_error = "Required param '{$name}' is empty!";
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    function getError () 
    {
        return $this->_check_error;
    }

    function has($field) 
    {
        return array_key_exists($field, $this->_query_params);
    }
    
    function get($name) 
    {
        return $this->_params[$name];
    }

    function set($name, $val) 
    {
        if ( array_key_exists($name, $this->_query_params) ) {
            $type = $this->_query_params[$name];
            if ( is_array($type) ) {
                if ( is_array($val) ) {
                    foreach ( $val as $k => $v ) {
                        $k = $name.'.'.$k;
                        if ( array_key_exists($k, $this->_query_params) ) {
                            $this->_params[$k] = $v;
                        } else {
                            throw new Exception("No such field '{$k}'");
                        }
                    }
                }
                else {
                    throw new Exception("Wrong argument for '{$name}'");
                }
            } else {
                $this->_params[$name] = $val;
            }
        } else {
            throw new Exception("No such field '{$name}'");
        }
        return $this;
    }

    function __call($name, $args)
    {
        if ( $this->has($name) ) {
            if ( empty($args) ) {
                return $this->get($name);
            }
            return $this->set($name, $args[0]);
        } else {
            throw new Exception("Unknown method '{$name}'\n");
        }
    }

    function queryParams() 
    {
        $query = array();
        $file_params = $this->getMetaData('file_params');
        $file_params = (empty($file_params) ? array() : array_flip($file_params));
        foreach ( $this->getMetaData('query_params') as $name => $v ) {
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

    static function cookData (&$data) 
    {
        $query_params = array();
        if ( !empty($data['require_params']) ) {
            $query_params = array_merge($query_params, $data['require_params']);
        }
        if ( !empty($data['optional_params']) ) {
            $query_params = array_merge($query_params, $data['optional_params']);
        }
        if ( !empty($data['file_params']) ) {
            $query_params = array_merge($query_params, $data['file_params']);
        }
        $data['query_params'] = array_flip($query_params);
        ## location.city => $query_params['location']['city'] = 1
        foreach ( $data['query_params'] as $name => $v ) {
            if ( ($pos = strpos($name, '.')) !== FALSE ) {
                $first = substr($name, 0, $pos);
                if ( !isset($data['query_params'][$first]) ) {
                    $data['query_params'][$first] = array();
                }
                $subary = &$data['query_params'][$first];
                if ( is_array($subary) ) {
                    $subary[substr($name, $pos+1)] = 1;
                }
            }
        }
        if ( !empty($data['fields']) ) {
            $expanded = array();
            foreach ( $data['fields'] as $tag => $val ) {
                self::_expandFields($data['fields'], $tag, $expanded);
            }
        }
    }
    
    private static function _expandFields(&$fields, $tag, &$expanded) 
    {
        if (array_key_exists($tag, $expanded)) {
            return;
        }
        $flat = array();
        foreach ( $fields[$tag] as $f ) {
            if ( substr($f, 0, 1) == ':' ) {
                self::_expandFields($fields, $f, $expanded);
                $flat = array_merge($flat, $fields[$f]);
            } else {
                array_push($flat, $f);
            }
        }
        $fields[$tag] = array_unique($flat);
        $expanded[$tag] = 1;
    }

    function parseResponse($res) 
    {
        return new Net_Top_Response($res, $this);
    }
}

