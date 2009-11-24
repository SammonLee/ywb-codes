<?php
class Net_Top
{
    protected $top_url;
    protected $top_apikey;
    protected $top_secretkey;
    static $params = array(
        'service_url' => null,
        'apikey' => null,
        'secret_key' => null
        );
    const TOP_VERSION = '1.0';

    /**
     * @param string $top_url 服务器地址
     * @param string $top_apikey 应用 API Key
     * @param string $top_secretkey 应用密钥
     */
    function __construct($top_url=null, $top_apikey=null, $top_secretkey=null)
    {
        $this->top_url = (!is_null($top_url)
                          ? $top_url
                          : (defined('TOP_SERVICE_URL') ? TOP_SERVICE_URL : '' ));
        $this->top_apikey = ( !is_null($top_apikey)
                              ? $top_apikey
                              : (defined('TOP_API_KEY') ? TOP_API_KEY : '' ) );
        $this->top_secretkey = ( !is_null($top_secretkey)
                                 ? $top_secretkey
                                 : (defined('TOP_SECRET_KEY') ? TOP_SECRET_KEY : ''));
    }

    /**
     * 设置 TOP 连接参数。
     * 通过设置连接参数，可以配合 factory 函数，在运行时自由指定连接参数
     * 
     * @param string $top_url 服务器地址
     * @param string $top_apikey 应用 API Key
     * @param string $top_secretkey 应用密钥
     */
    static function setParams($top_url, $top_apikey, $top_secretkey)
    {
        self::$params = array(
            'service_url' => $top_url,
            'apikey' => $top_apikey,
            'secret_key' => $top_secretkey
            );
    }

    /**
     * 构造 Net_Top 对象
     * factory 和直接使用 new 创建区别在于，使用 new 创建对象是依赖
     * 预定义的函数，而 factory 还依赖于 setParams 设置的连接参数
     * 
     * @return Net_Top
     */
    static function factory()
    {
        return new self(self::$params['service_url'],
                        self::$params['apikey'],
                        self::$params['secret_key']);
    }

    function getServiceUrl ()
    {
        return $this->top_url;
    }

    function setServiceUrl($top_url)
    {
        return $this->top_url = $top_url;
    }

    function getApikey ()
    {
        return $this->top_apikey;
    }

    function setApikey($top_apikey)
    {
        return $this->top_apikey = $top_apikey;
    }
    function getSecretkey ()
    {
        return $this->top_secretkey;
    }

    function setSecretkey($top_secretkey)
    {
        return $this->top_secretkey = $top_secretkey;
    }

    /**
     * 完成 API 调用
     * 
     * @param Net_Top_Request $req 请求参数
     * @return Net_Top_Response 请求响应
     */
    function request ($req)
    {
        if ( !$req->check() ) {
            die("Bad request: " . $req->getError() );
        }
        list ($query, $files) = $this->getParameters($req);
        if ( $req->getHttpMethod() == 'post' ) {
            $req->setRestUrl($this->top_url);
            $res = $this->post($this->top_url, $query, $files);
        }
        else {
            $url = $this->top_url . (empty($query) ? '' : '?' . http_build_query($query));
            $req->setRestUrl($url);
            $res = $this->get( $url );
        }
        return $req->parseResponse($res);
    }

    private function curlExec($ch)
    {
        $data = curl_exec($ch);
        $meta = curl_getinfo($ch);
        $curl_error = array();
        if ( curl_errno($ch) != CURLE_OK ) {
            $curl_error = array(
                'code' => curl_errno($ch),
                'msg' => curl_error($ch)
                );
        }
        curl_close($ch);
        return array($data, $meta, $curl_error);
    }

    protected function get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        return $this->curlExec($ch);
    }

    protected function post($url, $query, $files)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        foreach ( $files as $name => $file ) {
            $fp = fopen($file, 'r');
            $query[$name] = '@' . $file;
            curl_setopt($ch, CURLOPT_INFILE, $fp);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        return $this->curlExec($ch);
    }
    
    protected function getParameters($req)
    {
        $query = $req->getParameters();
        $query['method'] = $req->getMethod();
        $query['api_key'] = $this->top_apikey;
		date_default_timezone_set('Asia/Chongqing');
        $query['timestamp'] = date('Y-m-d H:i:s.000');
        $query['v'] = self::TOP_VERSION;
        $str = $this->top_secretkey;
        $files = array();
        ksort($query);
        foreach ( $query as $key => $val ) {
            if ( $req->isFile($key) ) { // file fields
                $files[$key] = $val;
                unset($query[$key]);
            }
            else {
                $str .= $key.$val;
            }
        }
        $query['sign'] = strtoupper(md5($str));
        $req->setQueryParameters(array_merge($query, $files));
        return array($query, $files);
    }

    /**
     * 创建 Net_Top_Request 对象，再调用 request 函数
     * 例如 $top->itemGet(array('iid' => $iid, ...))
     * 可以等价于：
     *  $req = Net_Top_Request::factory('ItemGet', array(..));
     *  $top->request($req);
     * 
     * @param array $args API 调用参数
     * @return Net_Top_Response 请求响应
     */
    function __call($name, $args)
    {
        $api_name = ucfirst($name);
        $req = Net_Top_Request::factory($api_name, $args[0]);
        return $this->request($req);
    }
}