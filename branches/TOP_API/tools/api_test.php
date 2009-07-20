<?php
require('config.inc');
error_log(date('c'). ' ' . var_export($_REQUEST, true) . "\n", 3, "param.log");
/*
$_REQUEST = array (
  'format' => 'php',
  'method' => 'taobao.user.get',
  'api_url' => 'sandbox',
  'api_soure' => '1',
  'app_key' => '系统分配',
  'app_secret' => '系统分配',
  'session' => '',
  'sip_http_method' => 'POST',
  'fields' => '"user_id,nick,sex,real_name,phone,mobile,email"',
  'nick' => '"alipublic01"',
  'ZDEDebuggerPresent' => 'php,phtml,php3',
  'PHPSESSID' => 'ngq7srs289iga2umpv6bcp8mj0',
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
    if ( $_REQUEST['sip_http_method'] && $req->getHttpMethod() != 'post') {
        $req->setHttpMethod($_REQUEST['sip_http_method']);
    }
    if ( !$req->check() ) {
        $result = array(
            'status' => 500,
            'msg' => $req->getError(),
            );
    } else {
        $res = $top->request($req);
        if ( $res->isError() && $res->getErrorType() != Net_Top_Response::REQUEST_ERROR ) {
            $result = array(
                'status' => 500,
                'msg' => $res->getMessage()
                );
        }
        else {
            save_user_params($req);
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
            $result['phpcode'] = gen_code($req);
        }
    }
}
echo json_encode($result);
// print_r($result);

function get_api_name ($method)
{
    $parts = explode('.', $method);
    array_shift($parts);
    return implode('', array_map('ucfirst', $parts));
}

function gen_code($req)
{
    ob_start();
    $api_name = $req->getApiName();
    $args = var_export($req->getParameters(), true);
    $args = preg_replace('/\n/sm', "\n    ", $args);
?>
require('config.inc');
$top = Net_Top::factory();
$req = Net_Top_Request::factory(
    '<?php echo $api_name ?>',
    <?php echo $args ?>

);
<?php if ( $req->getMetadata('http_method', 'get') != $req->getHttpMethod()) : ?>
$req->setHttpMethod('<?php echo $req->getHttpMethod() ?>');
<?php endif; ?>
$res = $top->request($req);
<?php
$code = ob_get_contents();
ob_end_clean();
return $code;
}
