<?php
require_once __DIR__ . '/../../app/bootstrap.php';
use App\Providers\DatabaseProvider;
use App\Providers\AuthProvider; // Importamos para checar a sessão

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit;
}

// Inicia a sessão para pegarmos quem está logado
AuthProvider::startSession();
$id_logado = $_SESSION['id_usuario'] ?? null;

$id_funcionario = $_POST['id_funcionario'] ?? null;
$status = $_POST['status'] ?? null;

// Validação básica
if (!$id_funcionario || !in_array($status, ['ativo', 'inativo'])) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

// =========================================================
// TRAVA DE SEGURANÇA: Não pode editar o próprio status
// =========================================================
if ($id_funcionario == $id_logado) {
    echo json_encode(['success' => false, 'message' => 'Ação bloqueada: Você não pode alterar o seu próprio status!']);
    exit;
}

try {
    $conn = DatabaseProvider::connect();
    
    // Atualiza apenas o status
    $stmt = $conn->prepare("UPDATE usuario SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id_funcionario);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Status atualizado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar.']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()]);
}