<?php
session_start();

require_once __DIR__ . '/../app/bootstrap.php';

use App\Repositories\MatriculaRepository;
use App\Providers\AuthProvider;

$user = AuthProvider::user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (MatriculaRepository::save($_POST, $user['id'])) {
        $_SESSION['success'] = 'Matrícula realizada com sucesso!';
    }

    header('Location: matricula.php');
    exit;
}