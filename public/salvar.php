<?php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Controllers\MatriculaController;

$resultado = MatriculaController::save();

if ($resultado) {
    header("Location: matricula.php?sucesso=1");
} else {
    header("Location: matricula.php?erro=1");
}

exit;