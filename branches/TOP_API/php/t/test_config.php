<?php
require_once('simpletest/autorun.php');
define('TOP_LIBPATH', realpath(dirname(__FILE__).'/../src/'));
require( TOP_LIBPATH . DIRECTORY_SEPARATOR . 'Net/Top/Autoload.php');
Net_Top_Autoload::register();

