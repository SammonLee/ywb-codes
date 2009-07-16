<?php
define('TOP_SERVICE_URL', '');
define('TOP_API_KEY', '');
define('TOP_SECRET_KEY', '');
define('TOP_LIBPATH', realpath(dirname(__FILE__).'/../src/'));
require( TOP_LIBPATH . DIRECTORY_SEPARATOR . 'Net/Top/Autoload.php');
Net_Top_Autoload::register();
