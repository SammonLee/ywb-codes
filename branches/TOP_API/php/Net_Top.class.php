<?php
class Net_Top
{
    protected $top_url;
    protected $top_appkey;
    protected $top_secretkey;
    static $params;
    const TOP_URL = 'http://gw.api.taobao.com/router/rest';
    const TOP_VERSION = '1.0';

    function __construct($top_url, $top_appkey, $top_secretkey)
    {
        $this->top_url = (is_null($top_url) ? self::TOP_URL : $top_url);
        $this->top_appkey = $top_appkey;
        $this->top_secretkey = $top_secretkey;
    }

    static function setParams($top_url, $top_appkey, $top_secretkey)
    {
        self::$params = array(
            'service_url' => $top_url,
            'appkey' => $top_appkey,
            'secret_key' => $top_secretkey
            );
    }

    static function factory()
    {
        if ( empty(self::$params) )
            die("call setParams first!");
        return new self(self::$params['service_url'],
                        self::$params['appkey'],
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

    function getAppkey ()
    {
        return $this->top_appkey;
    }

    function setAppkey($top_appkey)
    {
        return $this->top_appkey = $top_appkey;
    }
    function getSecretkey ()
    {
        return $this->top_secretkey;
    }

    function setSecretkey($top_secretkey)
    {
        return $this->top_secretkey = $top_secretkey;
    }

    function request ($req)
    {
        if ( !$req->check() ) {
            die("Bad request: " . $req->getError() );
        }
        list ($query, $files) = $this->queryParam($req);
        if ( $req->httpMethod() == 'post' ) {
            $res = $this->post($this->top_url, $query, $files);
        }
        else {
            if ( !empty($files) ) {
                die("Use post method if want to upload file!\n");
            }
            $res = $this->get( $this->top_url . (empty($query) ? '' : '?' . http_build_query($query)) );
        }
        return $req->parseResponse($res);
    }

    function get($url)
    {
        $ch = curl_init();
        var_dump($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($ch);
        $meta = curl_getinfo($ch);
        curl_close($ch);
        return array($data, $meta);
    }

    function post($url, $query, $files)
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
        print_r($query);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $data = curl_exec($ch);
        $meta = curl_getinfo($ch);
        curl_close($ch);
        return array($data, $meta);
    }

    function queryParam($req)
    {
        $query = $req->queryParams();
        $query['method'] = $req->apiMethod();
        $query['api_key'] = $this->top_appkey;
		date_default_timezone_set('Asia/Chongqing');
        $query['timestamp'] = date('Y-m-d H:i:s.000');
        $query['v'] = self::TOP_VERSION;
        $str = $this->top_secretkey;
        $files = array();
        ksort($query);
        foreach ( $query as $key => $val ) {
            if ( is_array($val) ) { // file fields
                $files[$key] = $val[0];
                unset($query[$key]);
            }
            else {
                $str .= $key.$val;
            }
        }
        $query['sign'] = strtoupper(md5($str));
        return array($query, $files);
    }
}