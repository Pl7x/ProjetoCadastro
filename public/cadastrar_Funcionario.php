<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Middlewares\AuthMiddleware;
use App\Providers\AuthProvider;
use App\Repositories\UserRepository;

AuthMiddleware::requireLogin();
AuthProvider::startSession();
$user = AuthProvider::user();

if (($user['tipo'] ?? '') !== 'admin') {
    redirect('painel.php');
}

$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (UserRepository::createEmployee($nome, $email, $senha)) {
        redirect('painel.php');
    }

    $errorMessage = 'Erro ao cadastrar funcionário. Verifique os dados e tente novamente.';
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionário - Sistema Escolar</title>
    <link rel="stylesheet" href="resources/css/painel.css">
</head>
<body>
    <header class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <h2>Sistema Escolar</h2>
            </div>
            <nav class="navbar-menu">
                <a href="painel.php" class="nav-link">Dashboard</a>
                <a href="matricula.php" class="nav-link">Matrícula</a>
                <a href="cadastrar_Funcionario.php" class="nav-link active">Cadastrar Funcionário</a>
            </nav>
            <div class="navbar-user">
                <span class="user-info"><?php echo htmlspecialchars($user['nome'] ?? 'Usuário', ENT_QUOTES, 'UTF-8'); ?> <span class="user-badge"><?php echo htmlspecialchars($user['tipo'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></span></span>
                <a href="logout.php" class="btn-logout">Sair</a>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="content-wrapper">
            <section class="welcome-section">
                <div class="welcome-content">
                    <h1>Novo funcionário</h1>
                    <p>Preencha o formulário abaixo para cadastrar um novo colaborador no sistema.</p>
                </div>
            </section>

            <?php if ($errorMessage) { ?>
                <section class="info-section error-section">
                    <h3>Erro no cadastro</h3>
                    <p><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
                </section>
            <?php } ?>

            <section class="form-section">
                <form method="POST" action="cadastrar_Funcionario.php">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="nome">Nome completo</label>
                            <input id="nome" type="text" name="nome" placeholder="Digite o nome" autocomplete="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" placeholder="Digite o email" autocomplete="email" required>
                        </div>
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input id="senha" type="password" name="senha" placeholder="Digite a senha" autocomplete="new-password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">Cadastrar funcionário</button>
                </form>
            </section>
        </div>
    </main>
</body>
</html>
