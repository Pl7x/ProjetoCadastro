<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Providers\AuthProvider;

AuthProvider::startSession();
if (AuthProvider::check()) {
    redirect('painel.php');
}

$errorMessage = $_SESSION['erro_login'] ?? '';
unset($_SESSION['erro_login']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Escolar</title>
    <link rel="stylesheet" href="resources/css/login.css">
</head>
<body>

<div class="container">
    <div class="login-card">
        <h2>Bem-vindo</h2>
        <p>Entre no sistema</p>

        <?php if ($errorMessage) { ?>
            <div class="erro"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>

        <form method="POST" action="login.php">
            <div class="input-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" placeholder="Digite seu email" autocomplete="email" required>
            </div>

            <div class="input-group">
                <label for="senha">Senha</label>
                <input id="senha" type="password" name="senha" placeholder="Digite sua senha" autocomplete="current-password" required>
            </div>

            <button type="submit">Entrar</button>
        </form>
    </div>
</div>

</body>
</html>