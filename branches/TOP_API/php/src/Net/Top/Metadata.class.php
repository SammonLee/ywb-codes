<?php
/**
 * Metadata struct:
 *   class: class for the api
 *   api_type: type of api
 *   method: taobao api method name
 *   fields: group for fetch fields, useful for complicate fields
 *   parameters
 *   list_tags
 *   is_secure: is session needed
 * 
 * @package 
 */
class Net_Top_Metadata
{
    static $data;

    function add($api_name, $api)
    {
        $all = array();
        if ( !isset($api['parameters']['other']) )
            $api['parameters']['other'] = array();
        /* add system parameter */
        $api['parameters']['other'][] = 'format';
        $api['parameters']['other'][] = 'session';
        
        foreach ( array('required', 'optional', 'file', 'other') as $type ) {
            if ( isset($api['parameters'][$type]) ) {
                foreach ( $api['parameters'][$type] as $name ) {
                    if ( !isset($all[$name]) )
                        $all[$name] = array();
                    if ( ($pos=strpos($name, ".")) !== false )
                        $all[substr($name, 0, $pos)]['struct'] = true;
                    $all[$name][$type] = true;
                }
                $api['parameters'][$type] = array_flip($api['parameters'][$type]);
            }
        }
        $api['parameters']['all'] = $all;
        self::$data[$api_name] = $api;
    }

    function has($api_name)
    {
        return isset(self::$data[$api_name]);
    }
    
    function &get($api_name)
    {
        return self::$data[$api_name];
    }

    function &getAll()
    {
        return self::$data;
    }
}
