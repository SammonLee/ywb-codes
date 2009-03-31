<?php
class Apps_Api_TopSip extends Apps_Api_Top
{
    const TOP_URL = 'http://sip.alisoft.com/sip/rest';
    const TOP_APPKEY = '';
    const TOP_SECRET = '';
    const TOP_VERSION = '1.0';
    
    function __construct($top_url=null, $top_appkey=null, $top_secret=null) 
    {
        $this->top_url = (is_null($top_url) ? self::TOP_URL : $top_url);
        $this->top_appkey = (is_null($top_appkey) ? self::TOP_APPKEY : $top_appkey);
        $this->top_secret = (is_null($top_appkey) ? self::TOP_SECRET : $top_secret);
    }
    
    function queryParam($req) 
    {
        $query = $req->queryParams();
        $query['sip_apiname'] = $req->apiMethod();
        $query['sip_appkey'] = $this->top_appkey;
        $query['sip_timestamp'] = date('Y-m-d H:i:s.000');
        $query['v'] = self::TOP_VERSION;
        if ( array_key_exists('session', $query) ) {
            $query['sip_sessionid'] = $query['session'];
            unset($query['session']);
        }
        $str = $this->top_secret;
        $files = array();
        ksort($query);
        foreach ( $query as $key => $val ) {
            if ( is_array($val) ) {
                $files[$key] = $val[0];
                unset($query[$key]);
            }
            else {
                $str .= $key.$val;
            }
        }
        $query['sip_sign'] = strtoupper(md5($str));
        return array($query, $files);
    }
}
