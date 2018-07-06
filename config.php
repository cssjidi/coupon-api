<?php
// HTTP
define('HTTP_SERVER', 'http://localhost:9090/');
//define('HTTP_SERVER', 'http://192.168.43.89:9090/');
// HTTPS
define('HTTPS_SERVER', 'http://localhost:9090/');
//define('HTTPS_SERVER', 'http://192.168.43.89:9090/');

// DIR
define('DIR_APPLICATION', 'E:/cjd/coupon/catalog/');
define('DIR_SYSTEM', 'E:/cjd/coupon/system/');
define('DIR_IMAGE', 'E:/cjd/coupon/image/');
define('DIR_STORAGE', 'E:/cjd/couponstorge/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_DATABASE', 'db_coupon');
define('DB_PORT', '3306');
define('DB_PREFIX', 'mcc_');

define('JWT_SECRET', 'd57OrDudfvTPqYCnjjKOvvfhXGYURJD6');