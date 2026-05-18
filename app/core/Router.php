<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, array $action, array $middlewares = []): void
    {
        $this->addRoute(['GET'], $uri, $action, $middlewares);
    }

    public function post(string $uri, array $action, array $middlewares = []): void
    {
        $this->addRoute(['POST'], $uri, $action, $middlewares);
    }

    public function match(array $methods, string $uri, array $action, array $middlewares = []): void
    {
        $this->addRoute($methods, $uri, $action, $middlewares);
    }

    private function addRoute(array $methods, string $uri, array $action, array $middlewares): void
    {
        $normalizedUri = '/' . trim($uri, '/');

        if ($normalizedUri === '/') {
            $normalizedUri = '/';
        }

        $this->routes[] = [
            'methods' => $methods,
            'uri' => $normalizedUri,
            'action' => $action,
            'middlewares' => $middlewares,
        ];
    }

    public function dispatch(string $requestUri, string $requestMethod): void
    {
        $path = parse_url($requestUri, PHP_URL_PATH) ?: '/';
        $basePath = parse_url((string) config('app.url', ''), PHP_URL_PATH) ?: '';

        if (substr($basePath, -10) === '/index.php') {
            $basePath = substr($basePath, 0, -10);
        }

        $basePath = rtrim($basePath, '/');

        if ($basePath !== '' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath)) ?: '/';
        }

        $path = '/' . trim($path, '/');

        if ($path === '') {
            $path = '/';
        }

        $pathMatched = false;

        foreach ($this->routes as $route) {
            if ($route['uri'] !== $path) {
                continue;
            }

            $pathMatched = true;

            if (!in_array($requestMethod, $route['methods'], true)) {
                continue;
            }

            Middleware::handle($route['middlewares']);

            [$controllerClass, $method] = $route['action'];
            $controller = new $controllerClass();
            $controller->{$method}();

            return;
        }

        if ($pathMatched) {
            http_response_code(405);

            View::render('errors.405', [
                'title' => 'Metodo no permitido',
            ]);

            return;
        }

        http_response_code(404);

        View::render('errors.404', [
            'title' => 'Pagina no encontrada',
        ]);
    }
}
