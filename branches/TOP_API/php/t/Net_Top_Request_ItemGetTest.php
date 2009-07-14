<?php
require_once('test_config.php');

class Net_Top_Request_ItemGetTest extends UnitTestCase
{
    function testConstructor()
    {
        $data = array(
                'fields' => 'iid',
                'nick' => 'me',
                'iid' => 'xxx',
                'format' => 'json'
            );
        $req = new Net_Top_Request_ItemGet($data);
        $this->assertTrue(is_a($req, 'Net_Top_Request_ItemGet'));
        $this->assertTrue(is_a($req, 'Net_Top_Request'));
        $this->assertEqual($req->getMethod(), 'taobao.item.get');
        $this->assertEqual($req->getApiName(), 'ItemGet');
        $this->assertEqual($req->getMetadata('api_type'), 'Item');
        $this->assertTrue($req->has('format'));
        $this->assertTrue($req->has('fields'));
        $this->assertTrue($req->isRequired('fields'));
        $this->assertFalse($req->isOptional('iid'));
        $this->assertEqual($req->get('nick'), $data['nick']);
        $this->assertEqual($req->getParameters(), $data);
    }

    function testConstructor2()
    {
        $data = array(
                'fields' => 'iid',
                'nick' => 'me',
                'iid' => 'xxx',
            );
        $req = new Net_Top_Request_ItemGet($data);
        $this->assertEqual($req->getParameters(), $data);
    }

    function testFactory()
    {
        $data = array(
                'fields' => 'iid',
                'nick' => 'me',
                'iid' => 'xxx',
            );
        $req = Net_Top_Request::factory('ItemGet', $data);
        $this->assertTrue(is_a($req, 'Net_Top_Request_ItemGet'));
        $this->assertEqual($req->getParameters(), $data);
    }
    
    function testFluence()
    {
        $data = array(
                'fields' => 'iid',
                'nick' => 'me',
                'iid' => 'xxx',
            );
        $req = new Net_Top_Request_ItemGet();
        $req->set('fields', $data['fields'])
            ->set('nick', $data['nick'])
            ->set('iid', $data['iid']);
        $this->assertEqual($req->getParameters(), $data);

        $req = new Net_Top_Request_ItemGet();
        $req->fields($data['fields'])
            ->nick($data['nick'])
            ->iid($data['iid']);
        $this->assertEqual($req->getParameters(), $data);
    }

    function testCheck()
    {
        $data = array(
                'fields' => 'iid',
                'nick' => 'me',
                'iid' => 'xxx',
            );
        $req = new Net_Top_Request_ItemGet();
        $req->set('fields', $data['fields'])
            ->set('nick', $data['nick']);
        $this->assertFalse($req->check());
        $this->assertEqual($req->getError(), "Require parameter 'iid'!");
        $req->set('iid', $data['iid']);
        $this->assertTrue($req->check());
        $this->assertNull($req->getError());
    }

    function testFields()
    {
        $data = array(
            'fields' => array(':small'),
            'nick' => 'me',
            'iid' => 'xxx',
            );
        $req = new Net_Top_Request_ItemGet($data);
        $params = $req->getParameters();
        $this->assertEqual($params['fields'], 'iid,title,nick,type,cid,num,price');

        $data['fields'] = array(':small', ':image', 'has_invoice');
        $req = new Net_Top_Request_ItemGet($data);
        $params = $req->getParameters();
        $this->assertEqual($params['fields'], 'iid,title,nick,type,cid,num,price,pic_path,itemimg,propimg,has_invoice');
    }
}
