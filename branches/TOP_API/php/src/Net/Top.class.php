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

    static function setParams($top_url, $top_apikey, $top_secretkey)
    {
        self::$params = array(
            'service_url' => $top_url,
            'apikey' => $top_apikey,
            'secret_key' => $top_secretkey
            );
    }

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

    function get($url)
    {
        $ch = curl_init();
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $data = curl_exec($ch);
        $meta = curl_getinfo($ch);
        curl_close($ch);
        return array($data, $meta);
    }

    function getParameters($req)
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

    function __call($name, $args)
    {
        $api_name = ucfirst($name);
        $req = Net_Top_Request::factory($api_name, $args[0]);
        return $this->request($req);
    }
}
