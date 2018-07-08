<?php
// Version
define('VERSION', '1.0.0.0');
echo 'power by cjd, version:'.VERSION;
// Configuration
if (is_file('config.php')) {
    require_once('config_api.php');
}

// define('PAY_METHOD_CALLBACK', '');
// date_default_timezone_set('PRC');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('api');
