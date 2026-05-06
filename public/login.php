<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Providers\AuthProvider;

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (AuthProvider::attempt($email, $senha)) {
    redirect('painel.php');
}

AuthProvider::startSession();
$_SESSION['erro_login'] = 'Email ou senha inválidos!';
redirect('index.php');