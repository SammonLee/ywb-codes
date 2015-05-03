# 简介 #

PHP TOP API 用户指南

# 安装 #

可以选择用 pear 安装：
```
$ pear install Net_Top.tar.gz
```

也可以直接复制 src 目录下的文件到 include\_path 目录下。

创建配置文件 config.inc:
```
$ cp demo/config.inc.example config.inc
```

**快速入门**

```
<?php
require('config.inc');
$nick = 'alipublic01';
$top = Net_Top::factory();
$req = Net_Top_Request::factory(
    'UserGet',
    array (
      'fields' => array(':all),
      'nick' => $nick,
    )
);
$res = $top->request($req);
if ( $res->isError() ) {
    echo "ERROR: " . $res->getMessage(), "\n";
} else {
    print_r($res->result());
}
```

第 1 行导入配置文件 config.inc，配置文件内容如下：
```
<?php
define('TOP_SERVICE_URL', 'http://gw.sandbox.taobao.com/router/rest');
define('TOP_API_KEY', 'test');
define('TOP_SECRET_KEY', 'test');
require('Net/Top/Autoload.php');
Net_Top_Autoload::register();
```

TOP\_SERVICE\_URL, TOP\_API\_KEY, TOP\_SECRET\_KEY 根据实际情况而设置。
Net\_Top\_Autoload 用于自动加载所有相关的类。

第 4 行创建一个 TOP 的客户端对象。

第 5 行创建一个 API 请求对象。第一个参数是 api 的名字，一般就是把 api
中第一个 taobao 去除之后首字母大写再拼接起来。第二个参数是 api 的所有
参数。一定的参数只需要设置对应的字符串值就可以了。fields 字段可以有多
种选择，可以是一个字符串，也可以是一个数组。如果是数组时，可以是 api
提供的 fields，也可以是冒号开头的 fields 组名，比如 ':all' 表示取所有
字段。

第 12 行使用 request 方法获得 api 的响应。

第 13 行检查此次请求是否出现错误，如果出错的话可以用 getMessage 方法得
到错误原因。否则可以使用 result 方法得到请求结果。