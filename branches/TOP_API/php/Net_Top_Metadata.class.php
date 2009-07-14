<?php
/**
 * Metadata struct:
 *   class: class for the api
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
        $api['parameters']['other'][] = 'format';
        foreach ( array('required', 'optional', 'file', 'other') as $type ) {
            if ( isset($api['parameters'][$type]) ) {
                foreach ( $api['parameters'][$type] as $name ) {
                    if ( !isset($all[$name]) )
                        $all[$name] = array();
                    $all[$name][$type] = true;
                }
                $api['parameters'][$type] = array_flip($api['parameters'][$type]);
            }
        }
        $api['parameters']['all'] = $all;
        self::$data[$api_name] = $api;
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
