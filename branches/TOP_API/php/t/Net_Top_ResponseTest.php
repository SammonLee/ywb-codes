<?php
require_once('test_config.php');

class Net_Top_ResponseTest extends UnitTestCase
{
    function setUp()
    {
        $this->req = Net_Top_Request::factory(
            'ItemGet',
            array(
                'fields' => 'iid',
                'nick' => 'me',
                'iid' => 'xxx',
                )
            );
    }
    
    function testJSON()
    {
        $this->req->format('json');
        $res = new Net_Top_Response(
            array(
                file_get_contents( 'fixture/item.get.json' ),
                array('http_code' => 200),
                array(),
                ),
            $this->req
            );
        print_r($res->result());
    }

    function testXML()
    {
        $res = new Net_Top_Response(
            array(
                file_get_contents( 'fixture/item.get.xml' ),
                array( 'http_code' => 200 ),
                array()
                ),
            $this->req
            );
        print_r($res->result());
    }
}
