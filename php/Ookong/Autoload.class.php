<?php
/**
 * 自动加载类
 * 
 * SYNOPOSIS:
 * <code>
 *     require('Ookong/Autoload.php');
 *     Ookong_Autoload::register();
 * </code>
 *
 * 自动加载规则:
 * <pre>
 *   Foo_Bar => OOKONG_AUTOLOAD_PATH/Foo/Bar.class.php
 * </pre>
 *
 * OOKONG_AUTOLOAD_PATH 常量可以在加载 Ookong_Autoload.php 文件之前定义，
 * 如果不定义，默认设置是 Ookong_Autoload.php 文件所在目录的父目录。
 * 
 * @copyright Copyright (c) 2009
 * @package Ookong
 * @subpackage utility
 * @author Ye Wenbin<wenbinye@gmail.com>
 */
if ( !defined('OOKONG_AUTOLOAD_PATH') ) {
    define('OOKONG_AUTOLOAD_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..').DIRECTORY_SEPARATOR);
}

class Ookong_Autoload {
    static $registered;
    static $instance;
    static $EXT = '.class.php';

    static public function register()
    {
        if ( self::$registered )
            return;
        ini_set('unserialize_callback_func', 'spl_autoload_call');
        if (false === spl_autoload_register(array(self::getInstance(), 'autoload')))
        {
            throw new Exception(sprintf('Unable to register %s::autoload as an autoloading method.',
                                        get_class(self::getInstance())));
        }
        self::$registered = true;
    }
    
    static public function getInstance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    static public function unregister()
    {
        spl_autoload_unregister(array(self::getInstance(), 'autoload'));
        self::$registered = false;
    }

    public function autoload( $class){
        $file = OOKONG_AUTOLOAD_PATH . str_replace('_', DIRECTORY_SEPARATOR, $class) . self::$EXT;
        // error_log($file);
        if( file_exists( $file ) ) {
            require $file;
            return true;
        }
        return false;
    }
}
