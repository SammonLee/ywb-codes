<?php
require_once('test_config.php');

class Net_Top_MetadataTest extends UnitTestCase
{
    function testGet()
    {
        $def = array(
                'method' => 'taobao.item.get',
                'class' => 'Net_Top_Request_ItemGet',
                'fields' => array(
                    ':all' => array(
                        'iid', 'nick', "location", "location.city"
                        ),
                    ),
                'parameters' => array(
                    'required' => array('fields', 'nick', 'iid', "location", "location.city")
                    )
            );
        Net_Top_Metadata::add(
            'ItemGet', $def
            );
        $data = Net_Top_Metadata::get('ItemGet');
        $this->assertTrue(is_array($data)
            && $data['class'] == $def['class']);
        $this->assertEqual(
            $data['parameters'],
            array (
                'required' => array ( 'fields' => 0, 'nick' => 1, 'iid' => 2, "location" => 3, "location.city" => 4),
                'other' => array ( 'format' => 0, ),
                'all' => array (
                    'fields' => array ( 'required' => true, ),
                    'nick' => array ( 'required' => true, ),
                    'iid' => array ( 'required' => true, ),
                    'format' => array ( 'other' => true, ),
                    'location' => array( 'required' => true, 'struct' => true ),
                    'location.city' => array( 'required' => true )
                    ),
                )
            );
        $data['class'] = 'ItemGet';
        $data = &Net_Top_Metadata::get('ItemGet');
        $this->assertEqual($data['class'], $def['class']);
        $data['class'] = 'ItemGet';
        $data = Net_Top_Metadata::get('ItemGet');
        $this->assertEqual($data['class'], 'ItemGet');
    }
}

    