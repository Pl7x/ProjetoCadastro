<?php

namespace App\Controllers;

use App\Providers\AuthProvider;
use App\Repositories\UserRepository;

class AdminController
{
    public static function registerEmployee(): void
    {
        AuthProvider::startSession();
        $user = AuthProvider::user();

        if (($user['tipo'] ?? '') !== 'admin') {
            redirect('painel.php');
        }

        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (UserRepository::createEmployee($nome, $email, $senha)) {
            redirect('painel.php');
        }

        echo 'Erro ao cadastrar funcionário.';
    }
}
