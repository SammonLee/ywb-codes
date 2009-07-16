<?php
if ( !defined('TOP_LIBPATH') ) {
    define('TOP_LIBPATH', realpath(
               implode( DIRECTORY_SEPARATOR,
                        array(dirname(__FILE__), '..', '..'))));
}

class Net_Top_Autoload 
{
    static $registered;
    static $instance;
    
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

    static public function getInstance($cacheFile = null)
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self($cacheFile);
        }
        return self::$instance;
    }

    static public function unregister()
    {
        spl_autoload_unregister(array(self::getInstance(), 'autoload'));
        self::$registered = false;
    }

    public function autoload($class)
    {
        $paths = explode('_',$class);
        array_unshift($paths, TOP_LIBPATH);
        $file = implode( DIRECTORY_SEPARATOR, $paths ) . '.class.php';
        if ( file_exists( $file ) ){
            require($file);
            return true;
        }
        return false;
    }
}
