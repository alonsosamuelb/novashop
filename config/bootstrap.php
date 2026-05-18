<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('VIEW_PATH', APP_PATH . '/views');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('ROUTES_PATH', BASE_PATH . '/routes');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

$appConfig = require CONFIG_PATH . '/app.php';

date_default_timezone_set($appConfig['timezone']);

if (session_status() === PHP_SESSION_NONE) {
    session_name('novashop_session');
    session_start();
}

if ($appConfig['debug']) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
}

require_once APP_PATH . '/helpers.php';
