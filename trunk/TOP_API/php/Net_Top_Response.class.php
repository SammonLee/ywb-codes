<?php
class Apps_Api_Response 
{
    protected $_data;
    protected $_metadata;
    protected $_request;
    protected $_result;
    
    function __construct($res, $req=null) 
    {
        $this->_data = $res[0];
        $this->_metadata = $res[1];
        $this->_request = $req;
        if ( !$this->isHttpError() ) {
            $this->parseXML();
        }
    }

    private static function xmlobjToArray($obj) 
    {
        if ( is_object($obj) ) {
            $obj = get_object_vars($obj);
        }
        if ( is_array($obj) ) {
            foreach ( $obj as $key => $value ) {
                $obj[$key] = self::xmlobjToArray($value);
            }
        }
        return $obj;
    }
    
    private function parseXML () 
    {
        $xml = simplexml_load_string($this->_data,'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOERROR);
        if ( $xml === FALSE ) {
            $this->_result = array(
                'code' => '999',
                'msg' => 'Server return malformed xml string',
                );
        }
        else {
            $this->_result = self::xmlobjToArray($xml);
        }
    }
    
    private function isHttpError () 
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
    
    public function message()
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
}
