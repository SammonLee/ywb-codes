<?php
abstract class Net_Top_Request
{
    static $factory_prefix = 'Net_Top_Request';
    static $response_class = 'Net_Top_Response';
    protected $api_name;
    protected $paramters;
    protected $api_parameters;
    protected $rest_url;
        
    function __construct( $args = null) 
    {
        // infer api name. First check self::$api_name,
        // if not set, use last word in class name
        $class = get_class($this);
        $static_vars = get_class_vars($class);
        if ( isset($static_vars['api_name']) ) {
            $this->api_name = $static_vars['api_name'];
        } else {
            $part = explode('_', $class);
            $this->api_name = array_pop($part);
        }
        // cache api_parameters for query and check faster
        $this->api_parameters = $this->getMetadata('parameters');
        if ( !empty($args) ) {
            foreach ( $args as $k => $v ) {
                $this->set($k, $v);
            }
        }
    }
    
    function factory($api_name, $args=null)
    {
        $class = self::$factory_prefix . '_' . $api_name;
        return new $class($args);
    }

    function parseResponse($res) 
    {
        $class = $this->getResponseClass();
        return new $class($res, $this);
    }

    function getResponseClass()
    {
        return self::$response_class;
    }

    function getHttpMethod()
    {
        return $this->getMetadata('http_method', 'get');
    }

    function has($name) 
    {
        return isset($this->api_parameters['all'][$name]);
    }

    function isFile($name)
    {
        return isset($this->api_parameters['file'][$name]);
    }

    function isRequired($name)
    {
        return isset($this->api_parameters['required'][$name]);
    }

    function isOptional($name)
    {
        return isset($this->api_parameters['optional'][$name]);
    }
    
    function get($name, $default=null) 
    {
        return isset($this->parameters[$name]) ? $this->parameters[$name] : $default;
    }

    function set($name, $val) 
    {
        if ( isset($this->api_parameters['all'][$name]) ) {
            $type = $this->api_parameters['all'][$name];
            if ( isset($type['struct']) ) { // struct fields, such as location.city
                if ( is_array($val) ) {
                    foreach ( $val as $k => $v ) {
                        $k = $name.'.'.$k;
                        if ( isset($this->api_parameters['all'][$k]) ) {
                            $this->parameters[$k] = $v;
                        } else { // not such structed fields
                            return false;
                        }
                    }
                } else { // struct fields need an array value
                    return false;
                }
            } else {
                $this->parameters[$name] = $val;
            }
        } else { // no such query fields
            return false;
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

    function check()
    {
        $this->error = null;
        if ( isset($this->api_parameters['required']) ) {
            foreach ( $this->api_parameters['required'] as $name => $i ) {
                if ( !isset($this->parameters[$name]) ) {
                    $this->error = "Require parameter '{$name}'!";
                    return false;
                }
            }
        }
        if ( isset($this->api_parameters['optional']) ) {
            $valid = false;
            foreach ( $this->api_parameters['optional'] as $name => $i ) {
                if ( isset($this->parameters[$name]) ) {
                    $valid = true;
                }
            }
            if ( !$valid ) {
                $this->error = sprintf("This parameters '%s' should given at least one.", implode(', ', array_keys($this->api_parameters['optional'])));
                return false;
            }
        }
        if ( $this->isSecure() && !isset($this->parameters['session']) ) {
                $this->error = "Authentication needed";
                return false;
        }
        return true;
    }

    function getError()
    {
        return $this->error;
    }
    
    function getParameters() 
    {
        $query = array();
        foreach ( $this->api_parameters['all'] as $name => $type ) {
            if ( isset($this->parameters[$name]) ) {
                if ( $name == 'fields' && is_array($this->parameters['fields']) ) {
                    $fields = $this->getMetadata('fields');
                    $all = array();
                    foreach ( $this->parameters['fields'] as $f ) {
                        if ( substr($f, 0, 1) == ':' ) {
                            if ( array_key_exists($f, $fields) ) {
                                $all = array_merge($all, $fields[$f]);
                            }
                            else { // ignore not exists fields
                               // throw new Exception("Unknown field tag '{$f}'\n");
                            }
                        } else {
                            array_push($all, $f);
                        }
                    }
                    $query[$name] = implode(',', array_unique($all));
                }
                else {
                    $query[$name] = (string)$this->parameters[$name];
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

    function isSecure()
    {
        return $this->getMetadata('is_secure', false);
    }

    function setRestUrl($url)
    {
        $this->rest_url = $url;
    }

    function getRestUrl($url)
    {
        return $this->rest_url;
    }
}
