<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $parts = explode('\\', $relativeClass);
    $parts[0] = strtolower($parts[0]);
    $file = APP_PATH . '/' . implode('/', $parts) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

$router = new App\Core\Router();

require ROUTES_PATH . '/web.php';

$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');
