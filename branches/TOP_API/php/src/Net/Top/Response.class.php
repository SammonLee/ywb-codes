<?php
/**
 * Error Type:
 *  - curl
 *  - service
 *  - system
 *  - application
 * 
 * @package 
 */
class Net_Top_Response 
{
    protected $_data;
    protected $_metadata;
    protected $_request;
    protected $_result;
    const SERVER_ERR = 1000;
    
    function __construct($res, $req) 
    {
        $this->_data = $res[0];
        $this->_metadata = $res[1];
        $this->_request = $req;
        if ( !$this->isHttpError() ) {
            $method = 'parseXML';
            $format = $this->_request->format();
            if ( $format ) {
                $method = 'parse' . strtoupper($format);
            }
            $this->$method();
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
            $this->serverError();
        }
    }

    private function serverError()
    {
        $this->_result = array(
            'code' => self::SERVER_ERR,
            'msg' => "Server return malformed data:\n".$this->_data,
            );
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
            $this->serverError();
        }
    }
    
    function isHttpError () 
    {
        return (4 == ($type = floor($this->_metadata['http_code'] / 100)) || 5 == $type);
    }
    
    public function isError()
    {
        if ($this->isHttpError()) {
            return TRUE;
        }
        return !empty($this->_result['code']);
    }

    public function content()
    {
        return is_string($this->_data) ? $this->_data : '';
    }

    public function result() 
    {
        return $this->_result;
    }
    
    public function getMessage()
    {
        if ( $this->isHttpError() ) {
            $msg = "HTTP request error with code {$this->_metadata['http_code']}";
        }
        if ( empty($this->_result['msg'] ) ) {
            $msg = "Unknown error with code {$this->_result['code']}";
        }
        else {
            $msg = $this->_result['msg'];
        }
        return $msg;
    }

    public function getUrl()
    {
        return $this->_request->getRestUrl();
    }

    public function getParameters()
    {
        return $this->_request->getQueryParameters();
    }
}
