<?php
define('TOP_SERVICE_URL', 'http://gw.sandbox.taobao.com/router/rest');
define('TOP_API_KEY', 'test');
define('TOP_SECRET_KEY', 'test');
define('TOP_LIBPATH', realpath(dirname(__FILE__).'/../php/src/'));
require( TOP_LIBPATH . DIRECTORY_SEPARATOR . 'Net/Top/Autoload.php');
Net_Top_Autoload::register();
require('helper.php');
session_start();
