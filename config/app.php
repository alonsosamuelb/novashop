<?php

return [
    'name' => 'NovaShop',
    'env' => 'local',
    'debug' => true,
    'timezone' => 'Europe/Madrid',
    'locale' => 'es_ES',

    'url' => 'http://localhost/novashop',
    'asset_url' => 'http://localhost/novashop/public/assets',

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
        'from_email' => 'noreply@localhost',
        'from_name' => 'NovaShop',
    ],
];
