<?php

namespace App\Controllers;

use App\Middlewares\AuthMiddleware;
use App\Providers\AuthProvider;
use App\Repositories\MatriculaRepository;

class MatriculaController
{
    public static function save(): bool
    {
        AuthMiddleware::requireLogin();
        $user = AuthProvider::user();

        if ($user === null) {
            redirect('index.php');
            return false;
        }

        return MatriculaRepository::save($_POST, (int) $user['id']);
    }
}