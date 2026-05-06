<?php

namespace App\Middlewares;

use App\Providers\AuthProvider;

class AuthMiddleware
{
    public static function requireLogin(): void
    {
        if (!AuthProvider::check()) {
            redirect('index.php');
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();

        $user = AuthProvider::user();
        if ($user === null || ($user['tipo'] ?? '') !== 'admin') {
            redirect('painel.php');
        }
    }
}
