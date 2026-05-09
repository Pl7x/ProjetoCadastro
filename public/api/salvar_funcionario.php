<?php
// 1. Carrega as configurações e a conexão
require_once __DIR__ . '/../../app/bootstrap.php';
use App\Providers\DatabaseProvider;

header('Content-Type: application/json');

// 2. Verifica se os dados vieram via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit;
}

// 3. Coleta os dados do formulário
$nome  = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$tipo  = $_POST['tipo'] ?? 'funcionario';
$senha = $_POST['senha'] ?? '';

// Validação básica
if (empty($nome) || empty($email) || empty($senha)) {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios!']);
    exit;
}

// ==========================================================
// 4. CRIPTOGRAFIA DA SENHA
// ==========================================================
// A função password_hash gera uma string segura de 60+ caracteres.
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);
// ==========================================================

try {
    $conn = DatabaseProvider::connect();

    // 5. Verifica se o email já existe para evitar duplicados
    $check = $conn->prepare("SELECT id FROM usuario WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Este e-mail já está cadastrado!']);
        exit;
    }

    // 6. Insere o novo usuário (com status ativo por padrão)
    $stmt = $conn->prepare("INSERT INTO usuario (nome, email, senha, tipo, status) VALUES (?, ?, ?, ?, 'ativo')");
    $stmt->bind_param("ssss", $nome, $email, $senhaHash, $tipo);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Funcionário cadastrado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar no banco: ' . $conn->error]);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()]);
}