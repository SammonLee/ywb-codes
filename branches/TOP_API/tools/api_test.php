<?php
define('TOP_SERVICE_URL', "http://api.daily.taobao.net/router/rest");
define('TOP_APPKEY', '597');
define('TOP_SECRET_KEY', 'LFeRyNaPMcEnmoFWJGxUYmrbXcdoMciM');
define('TOP_DIR', 'd:/repo/ywb-codes/branches/TOP_API/php');

function net_top_autoload($name)
{
    $file = TOP_DIR . DIRECTORY_SEPARATOR . $name . '.class.php';
    if ( file_exists( $file ) ){
        require($file);
    }
}
spl_autoload_register('net_top_autoload');

Net_Top::setParams(TOP_SERVICE_URL, TOP_APPKEY, TOP_SECRET_KEY);

error_log(date('c'). ' ' . var_export($_REQUEST, true) . "\n", 3, "param.log");
/* $_REQUEST = array (
  'format' => 'xml',
  'method' => 'taobao.user.get',
  'api_url' => 'sandbox',
  'api_soure' => '1',
  'app_key' => '系统分配',
  'app_secret' => '系统分配',
  'session' => '',
  'sip_http_method' => 'POST',
  'fields' => '"user_id,nick,sex,real_name,phone,mobile,email"',
  'nick' => '"tbtest1010"',
  'ZDEDebuggerPresent' => 'php,phtml,php3',
  ); */

$method = $_REQUEST['method'];
$api_name = get_api_name($method);

if ( $api_name ) {
    $top = Net_Top::factory();
    $format = strtolower($_REQUEST['format']);
    if ( $format == 'php' ) {
        unset($_REQUEST['format']);
    }
    foreach ( $_REQUEST as $key => $val ) {
        $_REQUEST[$key] = preg_replace('/^[\"](.*)[\"]$/', '$1', $val);
    }
    $req = Net_Top_Request::factory(
        $api_name, $_REQUEST
        );
    if ( $_REQUEST['sip_http_method'] ) {
        $req->setHttpMethod($_REQUEST['sip_http_method']);
    }
    if ( !$req->check() ) {
        $result = array(
            'status' => 500,
            'msg' => $req->getError(),
            );
    } else {
        $res = $top->request($req);
        if ( $res->isHttpError() ) {
            $result = array(
                'status' => 500,
                'msg' => $res->getError()
                );
        }
        else {
            $result['status'] = 200;
            if ($req->getHttpMethod() == 'get') {
                $result['param'] = $res->getUrl();
            }
            else {
                $result['param'] = $res->getUrl();
                foreach ( $res->getParameters() as $name => $v ) {
                    $result['param'] .= "\n" . $name . '=' . $v;
                }
            }
            if ( $format == 'php' ) {
                $result['content'] = print_r($res->result(), true);
            } else {
                $result['content'] = $res->content();
            }
        }
    }
}
echo json_encode($result);

function get_metadata()
{
    static $data;
    if ( !$data ) {
        $data = json_decode(file_get_contents("api_meta.json"), true);
    }
    return $data;
}

function get_api_name ($method)
{
    $metadata = get_metadata();
    foreach ( $metadata as $type => $apis ) {
        foreach ( $apis as $name => $api ) {
            if ( $name == $method ) {
                $part = explode('_', $api['class']);
                return array_pop($part);
            }
        }
    }
}
