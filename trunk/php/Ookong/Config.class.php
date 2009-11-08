<?php
/**
 * 带使用环境的配置处理
 *
 * SYNOPOSIS:
 * <code>
 *  $conf = array(
 *    'prod' => array( 'dbname' => 'prod_db' ),
 *    'dev' => array( 'dbname' => 'dev_db' ),
 *    'dbname' => 'default_db'
 *    );
 *  $config = new Ookong_Config($conf, 'prod');
 *  echo $config->env();         // output 'prod'
 *  echo $config->get('dbname'); // output 'prod_db'
 *  $config->env('dev');
 *  echo $config->env();         // output 'dev'
 *  echo $config->get('dbname'); // output 'dev_db'
 * </code>
 * 
 * @copyright Copyright (c) 2009
 * @package Ookong
 * @subpackage utility
 * @author Ye Wenbin<wenbinye@gmail.com>
 */
class Ookong_Config 
{
    private $config;
    private $env;
    /**
     * 环境名. 在配置项中不要使用这三个名字
     */
    static $ENVS = array('prod', 'dev', 'test');
    static $_instance;

    /**
     * 设置带环境的配置
     * 
     * @param array $config
     * @param string $env 环境名，默认为 prod
     */
    function __construct( $config, $env = 'prod' ) 
    {
        $this->config = $config;
        if ( empty($env) )
            die("Environment type is not given");
        $this->env($env);
    }

    static public function createInstance($config, $env='prod')
    {
        self::$_instance = new self($config, $env);
    }

    static public function getInstance()
    {
        if ( isset(self::$_instance) )
            return self::$_instance;
        else
            die(__CLASS__ ."is not initialized!");
    }
    
    /**
     * 将所有配置项按数组返回
     * 
     * @return array 所有配置项
     */
    function toArray()
    {
        $res = array_merge($this->config, $this->config[$this->env()]);
        foreach ( self::$ENVS as $env ) {
            unset($res[$env]);
        }
        return $res;
    }

    /**
     * 设置配置项
     * 
     * @param string $key 配置名
     * @param mixed $val 配置值
     */
    function set($key, $val)
    {
        return $this->config[$this->env()][$key] = $val;
    }

    /**
     * 获得配置项的值
     * 
     * @param string $key 配置名
     * @return mixed 配置值
     */
    function get($key)
    {
        $env = $this->env();
        if ( isset($this->config[$env]) && isset($this->config[$env][$key]) )
            return $this->config[$env][$key];
        if ( isset($this->config[$key]) )
            return $this->config[$key];
        return null;
    }

    /**
     * 查询或设置配置的 env。
     *
     * @param string $env 可选参数，如果设置，将设置环境为设置值
     * @return string $env
     */
    function env( )
    {
        if ( func_num_args() > 0 )
            $this->env = func_get_arg(0);
        return $this->env;
    }
}
