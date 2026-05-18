<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $userModel = new User();
        $user = $userModel->findActiveByEmail($email);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        if ((int) $user['activo'] !== 1) {
            return false;
        }

        session_regenerate_id(true);

        unset($user['password']);

        session_put('auth_user', $user);
        $userModel->touchLastAccess((int) $user['id']);

        return true;
    }

    public static function logout(): void
    {
        session_forget('auth_user');
        session_forget('cart');
        session_regenerate_id(true);
    }
}
