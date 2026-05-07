<?php
require_once __DIR__ . '/../../app/bootstrap.php';

use App\Providers\AuthProvider;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(['success' => false, 'message' => 'Email e senha são obrigatórios']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email inválido']);
    exit;
}

if (AuthProvider::attempt($email, $senha)) {
    echo json_encode(['success' => true, 'redirect' => 'painel.php']);
} else {
    echo json_encode(['success' => false, 'message' => 'Email ou senha inválidos']);
}
?>