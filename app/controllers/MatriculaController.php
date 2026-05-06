<?php

namespace App\Controllers;

use App\Middlewares\AuthMiddleware;
use App\Providers\AuthProvider;
use App\Repositories\MatriculaRepository;

class MatriculaController
{
    public static function save(): void
    {
        AuthMiddleware::requireLogin();
        $user = AuthProvider::user();

        if ($user === null) {
            redirect('index.php');
        }

        if (MatriculaRepository::save($_POST, (int) $user['id'])) {
            redirect('painel.php');
        }

        echo 'Erro ao salvar matrícula.';
    }
}
