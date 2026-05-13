<?php

require_once __DIR__ . '/../../app/bootstrap.php';

use App\Providers\DatabaseProvider;

header('Content-Type: application/json');

/* =========================================
   ERROS PHP
========================================= */

ini_set('display_errors', 0);

error_reporting(E_ALL);

/* =========================================
   MÉTODO
========================================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        'success' => false,
        'message' => 'Método inválido'
    ]);

    exit;
}

/* =========================================
   DADOS
========================================= */

$id_curso = $_POST['id_curso'] ?? null;

$status = $_POST['status'] ?? null;

/* =========================================
   VALIDAÇÃO
========================================= */

if (
    empty($id_curso) ||
    empty($status)
) {

    echo json_encode([
        'success' => false,
        'message' => 'Dados inválidos'
    ]);

    exit;
}

if (!in_array($status, ['ativo', 'inativo'])) {

    echo json_encode([
        'success' => false,
        'message' => 'Status inválido'
    ]);

    exit;
}

try {

    $conn = DatabaseProvider::connect();

    /* =====================================
       BUSCA CURSO
    ===================================== */

    $stmt = $conn->prepare("
        SELECT status
        FROM curso
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id_curso);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows <= 0) {

        echo json_encode([
            'success' => false,
            'message' => 'Curso não encontrado'
        ]);

        exit;
    }

    $curso = $result->fetch_assoc();

    /* =====================================
       STATUS REPETIDO
    ===================================== */

    if ($curso['status'] === $status) {

        echo json_encode([
            'success' => false,
            'message' => 'O curso já está com este status'
        ]);

        exit;
    }

    /* =====================================
       UPDATE
    ===================================== */

    $update = $conn->prepare("
        UPDATE curso
        SET status = ?
        WHERE id = ?
    ");

    $update->bind_param(
        "si",
        $status,
        $id_curso
    );

    if ($update->execute()) {

        echo json_encode([
            'success' => true,
            'message' => 'Status atualizado com sucesso!'
        ]);

    } else {

        echo json_encode([
            'success' => false,
            'message' => 'Erro ao atualizar status'
        ]);
    }

} catch (Throwable $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}