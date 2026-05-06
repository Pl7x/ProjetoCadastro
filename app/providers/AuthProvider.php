<?php

namespace App\Providers;

use App\Repositories\UserRepository;

class AuthProvider
{
    public static function startSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        \noCache();
    }

    public static function attempt(string $email, string $senha): bool
    {
        self::startSession();

        $user = UserRepository::findByEmailAndPassword($email, $senha);
        if ($user === null) {
            return false;
        }

        $_SESSION['id_usuario'] = $user['id'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['tipo'] = $user['tipo'];

        return true;
    }

    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        session_unset();
        session_destroy();
        \noCache();
    }

    public static function check(): bool
    {
        self::startSession();
        return isset($_SESSION['id_usuario']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return [
            'id' => $_SESSION['id_usuario'],
            'nome' => $_SESSION['nome'] ?? '',
            'tipo' => $_SESSION['tipo'] ?? '',
        ];
    }
}
