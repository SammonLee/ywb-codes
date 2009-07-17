<?php
class Net_Top_Response
{
    protected $_data;           /* 服务器返回数据 */
    protected $_metadata;       /* curl 请求信息 */
    protected $_curl_error;     /* curl 错误信息 */
    protected $_request;        /* 此次请求的 Net_Top_Request 对象 */
    protected $_result;         /* 解析结果 */
    protected $_error_type;     /* 错误类型 */
    protected $_error_code;     /* API 错误代码 */
    protected $_error_msg;  /* 错误信息 */
    const CURL_ERROR = 1;            /* curl错误，比如超时 */
    const HTTP_ERROR = 2;            /* http 错误，HTTP 返回状态不是200 */
    const REQUEST_ERROR = 3;         /* 接口中包含 code 错误 */
    const SERVICE_ERROR = 4;         /* 服务器HTTP返回状态是200,但是是不合法的返回值 */
    const UNKNOWN_CODE = 1000;       /* 非 REQUEST_ERROR 时的错误代码 */
    static $raise_exception = false; /* 在错误时是否抛出异常 */
    static $error_lang = 'en';       /* 错误提示语言：en | zh */
    static $request_errors = array(
        3  => array('Upload fail', '上传附件失败'),
        5  => array('Session Call limited', '用户会话期呼叫频度受限'),
        9  => array('Http action not allowed', '该方法不允许使用此Http动作'),
        10  => array('Service currently unavailable', '服务不可用'),
        11  => array('Insufficient ISV permissions', '第三方程序权限不够'),
        12  => array('Insufficient user permissions', '用户权限不够'),
        21  => array('Missing Method', '方法丢失'),
        22  => array('Invalid Method', '方法无效'),
        23  => array('Invalid Format', '响应格式无效'),
        24  => array('Missing signature', '签名丢失'),
        25  => array('Invalid signature', '签名无效'),
        26  => array('Missing session', '会话期识别码丢失'),
        27  => array('Invalid session', '会话期识别码无效'),
        28  => array('Missing API Key', 'App_Key丢失'),
        29  => array('Invalid API Key', 'App_Key无效'),
        30  => array('Missing timestamp', '时间戳丢失'),
        31  => array('Invalid timestamp', '时间戳无效'),
        32  => array('Missing version', '版本丢失'),
        33  => array('Invalid version', '版本错误'),
        40  => array('Missing required arguments', '参数丢失，指除 method ,session ,timestamp ,format ,app_key ,v ,sign外的其他参数丢失'),
        41  => array('Invalid arguments', '参数格式错误'),
        550  => array('User service unvailable', '用户数据服务不可用'),
        551  => array('Item service unvailable', '商品数据服务不可用'),
        552  => array('Item image service unvailable', '商品图片数据服务不可用'),
        553  => array('Item simple update service unavailable', '上下架，推荐，取消推荐 服务不可用'),
        560  => array('Trade service unvailable', '交易数据服务不可用'),
        590  => array('Shop service unavailable', '店铺服务不可用'),
        591  => array('Shop showcase remainCount unavailable', '店铺剩余推荐数 服务不可用'),
        601  => array('User not exist', '用户不存在'),
        );

    /**
     * @param array $res curl 相关信息：0 请求结果; 1 请求信息; 2 错误信息
     * @param Net_Top_Request $req
     */
    function __construct($res, $req)
    {
        $this->_data = $res[0];
        $this->_metadata = $res[1];
        $this->_curl_error = $res[2];
        $this->_request = $req;
        if ( isset($this->_curl_error['code']) ) {
            $this->setError( self::CURL_ERROR, "CURL ERROR {$this->_curl_error['code']}: {$this->_curl_error['msg']}" );
        } elseif ( $this->isHttpError() ) {
            $this->setError( self::REQUEST_ERROR, "HTTP ERROR {$this->_metadata['http_code']}" );
        }
        else {
            $method = 'parseXML';
            $format = $this->_request->format();
            if ( $format ) {
                $method = 'parse' . strtoupper($format);
            }
            $this->$method();
            if ( isset($this->_result['code']) ) {
                if ( !empty($this->_result['msg']) ) {
                    if ( self::$error_lang == 'zh'
                         && preg_match('/:(.*)/', $this->_result['msg'], $matches) ) {
                        $this->_result['msg'] = $matches[1];
                    }
                } else {
                    if ( isset(self::$request_errors[$this->_result['code']]) ) {
                        $this->_result['msg'] = self::$request_errors[$this->_result['code']][self::$error_lang == 'en' ? 0 : 1];
                    }
                    else {
                        $this->_result['msg'] = self::$error_lang == 'en'
                            ? "Unknown error with code {$this->_result['code']}"
                            : "未知错误代码 {$this->_result['code']}";
                    }
                }
                $this->setError(self::REQUEST_ERROR, $this->_result['msg']);
            }
        }
    }

    private static function isValidXML($str)
    {
        return substr($str, 0, 5) == '<?xml';
    }

    private static function isValidJSON($str)
    {
        return substr($str, 0, 1) == '{';
    }

    private static function xmlobjToArray($obj, $force_array_fields)
    {
        if ( is_object($obj) ) {
            $obj = get_object_vars($obj);
        }
        if ( is_array($obj) ) {
            foreach ( $obj as $key => $value ) {
                $obj[$key] = self::xmlobjToArray($value, $force_array_fields);
                if ( isset($force_array_fields[$key]) && !isset($obj[$key][0]) )
                    $obj[$key] = array($obj[$key]);
            }
        }
        return $obj;
    }

    private function parseXML ()
    {
        if ( self::isValidXML($this->_data) ) {
            $xml = simplexml_load_string($this->_data,'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOERROR);
            $list_tag = $this->_request->getMetaData('list_tags');
            if ( !empty($list_tag) )
                $list_tag = array_flip($list_tag);
            else
                $list_tag = array();
            $xml = self::xmlobjToArray($xml, $list_tag);
            $this->_result = $xml;
        }
        else {
            $this->setError( self::SERVICE_ERROR, "Server return malformed XML" );
        }
    }

    private function parseJSON ()
    {
        if ( self::isValidJSON($this->_data) ) {
            $json = json_decode($this->_data, true);
            if ( isset($json['rsp']) ) {
                $this->_result = $json['rsp'];
            }
            else {
                $this->_result = $json;
            }
        }
        else {
            $this->setError( self::SERVICE_ERROR, "Server return malformed JSON" );
        }
    }

    private function isHttpError ()
    {
        return (4 == ($type = floor($this->_metadata['http_code'] / 100)) || 5 == $type);
    }

    private function setError($error_type, $msg)
    {
        $this->_error_type = $error_type;
        $this->_error_msg = $msg;
        if ( $this->_error_type === self::REQUEST_ERROR ) {
            $this->_error_code = $this->_result['code'];
        }
        else {
            $this->_error_code = self::UNKNOWN_CODE;
        }
        if ( self::$raise_exception ) {
            static $exceptions = array(
                null,
                'Net_Top_CurlException',
                'Net_Top_HttpException',
                'Net_Top_RequestException',
                'Net_Top_ServiceException'
                );
            $exception_class = $exceptions[$this->_error_type];
            throw new $exception_class($this->_error_code, $this->_error_msg);
        }
    }

    /**
     * 判断请求是否出现错误
     *
     * @return bool
     */
    public function isError()
    {
        return !empty($this->_error_type);
    }

    /**
     * 返回出错信息
     */
    public function getMessage()
    {
        return $this->_error_msg;
    }

    /**
     * 返回错误类型
     */
    public function getErrorType()
    {
        return $this->_error_type;
    }

    /**
     * 返回错误代码
     */
    public function getErrorCode()
    {
        return $this->_error_code;
    }

    /**
     * 返回响应的原始数据
     */
    public function content()
    {
        return is_string($this->_data) ? $this->_data : '';
    }

    /**
     * 返回响应结果
     */
    public function result()
    {
        return $this->_result;
    }

    /**
     * 返回请求使用的 url
     */
    public function getUrl()
    {
        return $this->_request->getRestUrl();
    }

    /**
     * 返回请求使用的全部参数
     */
    public function getParameters()
    {
        return $this->_request->getQueryParameters();
    }
}

class Net_Top_CurlException extends Exception
{
}

class Net_Top_HttpException extends Exception
{
}

class Net_Top_RequestException extends Exception
{
}

class Net_Top_ServiceException extends Exception
{
}
