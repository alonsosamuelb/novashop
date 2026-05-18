<?php

return [
    'name' => 'NovaShop',
    'env' => getenv('APP_ENV') ?: 'local',
    'debug' => filter_var(getenv('APP_DEBUG') ?: 'true', FILTER_VALIDATE_BOOLEAN),
    'timezone' => getenv('APP_TIMEZONE') ?: 'Europe/Madrid',
    'locale' => getenv('APP_LOCALE') ?: 'es_ES',

    'url' => getenv('APP_URL') ?: 'http://localhost/novashop',
    'asset_url' => getenv('ASSET_URL') ?: 'http://localhost/novashop/public/assets',

    'currency' => 'EUR',
    'currency_symbol' => 'EUR',

    'pagination' => [
        'products_per_page' => 9,
        'orders_per_page' => 10,
        'admin_per_page' => 12,
    ],

    'roles' => [
        'cliente',
        'empleado',
        'administrador',
    ],

    'order_statuses' => [
        'pendiente',
        'enviado',
        'entregado',
    ],

    'featured_limit' => 8,

    'shipping' => [
        'base_cost' => 4.99,
    ],

    'upload' => [
        'product_dir' => 'uploads/products',
        'category_dir' => 'uploads/categories',
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
        'allowed_mime_types' => ['image/jpeg', 'image/png', 'image/webp'],
        'max_size' => 2 * 1024 * 1024,
    ],

    'mail' => [
        'enabled' => true,
        'driver' => 'simulated_log',
        'from_email' => getenv('MAIL_FROM_EMAIL') ?: 'noreply@localhost',
        'from_name' => getenv('MAIL_FROM_NAME') ?: 'NovaShop',
    ],
];
