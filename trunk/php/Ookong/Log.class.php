<?php
/**
 * 记录日志
 *
 * SYNOPOSIS:
 * <code>
 *    $logger = Ookong_Log::getLogger();
 *    $logger->debug("debug message");
 *    $logger->info("info message");
 *    $logger->err("error message");
 * </code>
 *
 * 配置设置示例：
 * <code>
 *  Ookong_Log::setDefaults( array( 'debug' => true, 'level' => 'INFO' ) ); // 设置默认值
 *  Ookong_Log::initConfig(       // 一次设置多个 Logger 配置
 *    array(
 *      'DAO' => array( 'debug' => true ),
 *      'Memorize' => array( 'debug' => true )
 *    )
 *  ); 
 *  Ookong_Log::setConfig('DAO', array('debug'=>true)); // 设置某个logger配置
 * </code>
 *
 * @copyright Copyright (c) 2009
 * @package Ookong
 * @subpackage utility
 * @author Ye Wenbin<wenbinye@gmail.com>
 */
class Ookong_Log
{
    private static $STATICS  = array('ERR'     => 4,
                                     'WARNING' => 3,
                                     'INFO'    => 2,
                                     'DEBUG'   => 1 );
    private $debug;
    private $appender;
    private $logLevel;
    static $DEFAULTS = array(
        'appender' => 'console',
        'level' => 'DEBUG',
        'debug' => false
        );
    static $config = array();
    static $loggers = array();
    const CALLER = 1;

    /**
     * 创建 Logger
     * 
     * 配置选项：
     * <ul>
     *  <li>debug: boolean isDebug 函数返回这个选项值。当为 true 时，日志输出时将标识日志调用代码位置
     *  <li>level: enum 可选值从高到低为 ERR, WARNING, INFO, DEBUG。在设置高级别的值时，将忽略低级别的日志项
     *  <li>appender: 目前可选值 console, file
     *  <li>file: 是设置 appender=file 时，输出日志到此文件
     * </ul>
     * 
     * @param array $conf 配置
     */
    function __construct($conf=array())
    {
        $conf = array_merge(self::$DEFAULTS, $conf);
        $this->appender = $this->createAppender($conf);
        $this->setLevel($conf['level']);
        $this->debug = $conf['debug'];
    }

    function createAppender($conf)
    {
        if ( isset($conf['appender']) )
            $class = 'Ookong_Log_Appender_' . ucfirst($conf['appender']);
        else
            $class = 'Ookong_Log_Appender_Console';
        if ( class_exists( $class ) ) {
            return new $class($conf);
        }
    }
    
    /**
     * 默认配置选项
     * 
     * @param array $conf
     */
    public static function setDefaults($conf)
    {
        self::$DEFAULTS = array_merge( self::$DEFAULTS, $conf );
    }

    /**
     * 初始化多个 Logger 配置
     * 
     * @param array $conf_set
     */
    public static function initConfig($conf_set)
    {
        self::$config = $conf_set;
    }

    /**
     * 初始化特定 Logger 的配置
     * 
     * @param string $name Logger 名字
     * @param array $conf 配置
     */
    public static function setConfig($name, $conf)
    {
        self::$config[$name] = $conf;
    }

    /**
     * 获得 Logger
     *
     * @param String $name Logger 名。如果调用此函数的位置位于某个类中，$name 默认值是类的名字。如果不在类中，使用 'main' 作名字
     * @return Log 
     */
    public static function getLogger($name=null)
    {
        if ( is_null($name) ) {
            $stack = debug_backtrace();
            $name = isset($stack[self::CALLER]['class']) ? $stack[self::CALLER]['class'] : 'main';
        }
        if ( !isset(self::$loggers[$name]) ) {
            $conf = isset(self::$config[$name]) ? self::$config[$name] : self::$DEFAULTS;
            self::$loggers[$name] = new self($conf);
        }
        return self::$loggers[$name];
    }

    /**
     * 设置 logger 输出等级
     * 
     * @param string $level
     * @return Ookong_Log 对象本身
     */
    function setLevel($level)
    {
        $level = strtoupper($level);
        if ( !isset(self::$STATICS[$level]) )
            die("Unknown log level '{$level}'");
        $this->logLevel = $level;
        return $this;
    }

    /**
     * @return string logger 日志等级
     */
    function getLevel()
    {
        return $this->level;
    }

    /**
     * 设置 logger 调试开关
     * 
     * @param boolean $flag 
     * @return Ookong_Log 对象本身
     */
    function setDebug($flag)
    {
        $this->debug = $flag;
        return $this;
    }

    /**
     * @return boolean 
     */
    function isDebug()
    {
        return $this->debug;
    }

    /**
     * 设置日志输出对象
     * 
     * @param Ookong_LogBase $appender 
     * @return Ookong_Log 对象本身
     */
    function setAppender($appender)
    {
        $this->appender = $appender;
        return $this;
    }

    /**
     * @return Ookong_LogBase
     */
    function getAppender()
    {
        return $this->appender;
    }
    
    private function log($message, $priority, $debug=null)
    {
        if ( self::$STATICS[$priority] >= self::$STATICS[$this->logLevel])
        {
            if ( $debug || (is_null($debug) && $this->debug) ) {
                $stack = debug_backtrace();
                $CALLER_INFO = self::CALLER + 1;
                
                $file = $stack[self::CALLER]['file'];
                $line = $stack[self::CALLER]['line'];
                $function = 'main';
                if ( count($stack) > $CALLER_INFO ) {
                    $function = $stack[$CALLER_INFO]['function'];
                    if ( isset($stack[$CALLER_INFO]['class']) )
                        $function = $stack[$CALLER_INFO]['class'].'::'.$function;
                }
                $message .= " ({$priority} at {$function} in $file line $line)";
            }
            $this->appender->log($message);
        }
    }

    /**
     * Logs an error message.
     *
     * @param string Message
     */
    public function err($message, $debug=true)
    {
        $this->log($message, 'ERR', $debug);
    }

    /**
     * Logs a warning message.
     *
     * @param string Message
     */
    public function warning($message, $debug=null)
    {
        $this->log($message, 'WARNING', $debug);
    }

    /**
     * Logs an info message.
     *
     * @param string Message
     */
    public function info($message, $debug=null)
    {
        $this->log($message, 'INFO', $debug);
    }

    /**
     * Logs a debug message.
     *
     * @param string Message
     */
    public function debug($message, $debug=null)
    {
        $this->log($message, 'DEBUG', $debug);
    }
}

/**
 * 日志输出 Appender
 */
interface Ookong_Log_IAppender
{
    function log($msg);
}

class Ookong_Log_Appender_Console implements Ookong_Log_IAppender
{
    static $DATE_FORMAT = 'Y-m-d H:i:s';
    private $is_cli;
    function __construct()
    {
        if ( !isset($_SERVER['HTTP_HOST']) ) {
            $this->is_cli = true;
        }
    }
    
    function log($msg)
    {
        error_log( ($this->is_cli ? date(self::$DATE_FORMAT) . ' ' : '') . $msg);
    }
}

class Ookong_Log_Appender_File implements Ookong_Log_IAppender
{
    static $DATE_FORMAT = 'c';
    private $file;
    function __construct($conf)
    {
        if ( !isset($conf['file']) ) {
            die("File name is need for file appender!");
        }
        $this->file = $file;
    }

    function log($msg)
    {
        error_log(date(self::$DATE_FORMAT) . ' ' . $msg . "\n", 3, $this->file);
    }
}
