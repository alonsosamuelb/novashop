<?php

declare(strict_types=1);

function config(string $key, mixed $default = null): mixed
{
    static $configs = [];

    if (empty($configs)) {
        $configs['app'] = require CONFIG_PATH . '/app.php';
        $configs['database'] = require CONFIG_PATH . '/database.php';
    }

    $segments = explode('.', $key);
    $value = $configs;

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}

function app_url(string $path = ''): string
{
    $baseUrl = rtrim((string) config('app.url', ''), '/');
    $path = ltrim($path, '/');

    return $path === '' ? $baseUrl : $baseUrl . '/' . $path;
}

function asset_url(string $path): string
{
    $assetBaseUrl = rtrim((string) config('app.asset_url', ''), '/');
    $path = ltrim($path, '/');

    return $path === '' ? $assetBaseUrl : $assetBaseUrl . '/' . $path;
}

function product_image_url(string $filename): string
{
    $relativePath = 'img/products/' . ltrim($filename, '/');
    $url = asset_url($relativePath);
    $filePath = public_path('assets/' . $relativePath);

    if (is_file($filePath)) {
        return $url . '?v=' . filemtime($filePath);
    }

    return $url;
}

function redirect(string $path): never
{
    header('Location: ' . app_url($path));
    exit;
}

function back(): never
{
    $fallback = app_url();
    $location = $_SERVER['HTTP_REFERER'] ?? $fallback;

    header('Location: ' . $location);
    exit;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function with_old(array $data): void
{
    $_SESSION['_old'] = $data;
}

function clear_old(): void
{
    unset($_SESSION['_old']);
}

function session_get(string $key, mixed $default = null): mixed
{
    return $_SESSION[$key] ?? $default;
}

function session_put(string $key, mixed $value): void
{
    $_SESSION[$key] = $value;
}

function session_forget(string $key): void
{
    unset($_SESSION[$key]);
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }

    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);

    return $value;
}

function csrf_token(): string
{
    if (empty($_SESSION['_token'])) {
        $_SESSION['_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): bool
{
    $token = $_POST['_token'] ?? '';

    return hash_equals($_SESSION['_token'] ?? '', (string) $token);
}

function is_post(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function auth_user(): ?array
{
    return session_get('auth_user');
}

function is_logged_in(): bool
{
    return auth_user() !== null;
}

function has_role(string|array $roles): bool
{
    $user = auth_user();

    if (!$user) {
        return false;
    }

    $roles = (array) $roles;

    return in_array($user['rol'], $roles, true);
}

function format_price(float|int|string $amount): string
{
    return number_format((float) $amount, 2, ',', '.') . ' €';
}

function current_path(): string
{
    $path = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
    $basePath = rawurldecode(parse_url((string) config('app.url'), PHP_URL_PATH) ?: '');

    if (substr($basePath, -10) === '/index.php') {
        $basePath = substr($basePath, 0, -10);
    }

    $basePath = rtrim($basePath, '/');

    if ($basePath !== '' && strpos($path, $basePath) === 0) {
        $path = substr($path, strlen($basePath)) ?: '/';
    }

    return '/' . trim($path, '/');
}

function is_active_route(string $uri): bool
{
    $expected = '/' . trim($uri, '/');
    return current_path() === $expected;
}

function slugify(string $value): string
{
    $value = trim($value);
    $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value;
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?: '';
    $value = trim($value, '-');

    return $value !== '' ? $value : 'item';
}

function public_path(string $path = ''): string
{
    return rtrim(PUBLIC_PATH . '/' . ltrim($path, '/'), '/');
}

function cart_items(): array
{
    $cart = session_get('cart', []);
    return is_array($cart) ? $cart : [];
}

function cart_count(): int
{
    return array_sum(array_map(static function ($qty): int {
        return (int) $qty;
    }, cart_items()));
}
