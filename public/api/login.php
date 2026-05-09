<?php
require_once __DIR__ . '/../../app/bootstrap.php';

use App\Providers\AuthProvider;
use App\Providers\DatabaseProvider; // <-- Importamos o banco de dados aqui

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

// =========================================================================
// PASSO 2: TRAVA DE SEGURANÇA (Verifica o status antes de logar)
// =========================================================================
try {
    $conn = DatabaseProvider::connect();

    // Busca apenas o ID e o status do usuário pelo email digitado
    $stmt = $conn->prepare("SELECT id, status FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario_db = $resultado->fetch_assoc();

    if ($usuario_db) {
        // Se o usuário existir, mas estiver com status INATIVO, bloqueia na hora!
        if ($usuario_db['status'] === 'inativo') {
            echo json_encode(['success' => false, 'message' => 'Sua conta está inativa. Contate a administração.']);
            exit; // Para a execução do código aqui, ele nem chega a verificar a senha
        }
    }
    $stmt->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro interno ao validar a conta.']);
    exit;
}
// =========================================================================

// Se passou pela trava do status, o sistema tenta fazer o login com a senha
if (AuthProvider::attempt($email, $senha)) {
    
    // =========================================================================
    // SUCESSO! GRAVA O ÚLTIMO LOGIN
    // =========================================================================
    if (isset($usuario_db) && isset($usuario_db['id'])) {
        $id_usuario = $usuario_db['id'];
        // Atualiza a coluna ultimo_login para a data e hora exatas de agora
        $conn->query("UPDATE usuario SET ultimo_login = NOW() WHERE id = $id_usuario");
    }
    // =========================================================================

    echo json_encode(['success' => true, 'redirect' => 'painel.php']);
} else {
    echo json_encode(['success' => false, 'message' => 'Email ou senha inválidos']);
}
?>