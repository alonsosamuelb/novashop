<?php

declare(strict_types=1);

namespace App\Core;

class Middleware
{
    public static function handle(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            if ($middleware === 'auth') {
                self::requireAuth();
                continue;
            }

            if ($middleware === 'guest') {
                self::requireGuest();
                continue;
            }

            if (str_starts_with($middleware, 'role:')) {
                $roles = explode(',', str_replace('role:', '', $middleware));
                self::requireRole($roles);
            }
        }
    }

    private static function requireAuth(): void
    {
        if (!is_logged_in()) {
            flash('error', 'Debes iniciar sesion para acceder a esa pagina.');
            redirect('login');
        }
    }

    private static function requireGuest(): void
    {
        if (is_logged_in()) {
            flash('success', 'Ya tienes una sesion iniciada.');
            redirect('');
        }
    }

    private static function requireRole(array $roles): void
    {
        if (!has_role($roles)) {
            http_response_code(403);
            View::render('errors.403', ['title' => 'Acceso denegado']);
            exit;
        }
    }
}
