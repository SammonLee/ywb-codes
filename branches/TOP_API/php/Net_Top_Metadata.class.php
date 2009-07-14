<?php
class Net_Top_Metadata
{
    static $data;

    function add($name, $api)
    {
        self::$data[$name] = $api;
    }

    function &get($name)
    {
        return self::$data[$name];
    }
}
