<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Providers\AuthProvider;
use App\Middlewares\AuthMiddleware;

AuthMiddleware::requireLogin();

$user = AuthProvider::user();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Sistema Escolar</title>
    <link rel="stylesheet" href="resources/css/painel.css">
</head>
<body>
    <header class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <h2>Sistema Escolar</h2>
            </div>
            <nav class="navbar-menu">
                <a href="painel.php" class="nav-link active">Dashboard</a>
                <a href="matricula.php" class="nav-link">Matrícula</a>
                <?php if (($user['tipo'] ?? '') === 'admin') { ?>
                    <a href="cadastrar_Funcionario.php" class="nav-link">Cadastrar Funcionário</a>
                <?php } ?>
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
                    <h1>Bem-vindo, <?php echo htmlspecialchars($user['nome'] ?? 'Usuário', ENT_QUOTES, 'UTF-8'); ?></h1>
                    <p>Você está logado como <strong><?php echo htmlspecialchars($user['tipo'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></strong></p>
                </div>
            </section>

            <section class="quick-stats">
                <div class="stat-item">
                    <span class="stat-label">Nome</span>
                    <p class="stat-value"><?php echo htmlspecialchars($user['nome'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Tipo de Acesso</span>
                    <p class="stat-value"><?php echo htmlspecialchars($user['tipo'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Status</span>
                    <p class="stat-value status-active">Ativo</p>
                </div>
            </section>

            <?php if (($user['tipo'] ?? '') === 'admin') { ?>
                <section class="info-section">
                    <h3>Gerenciamento</h3>
                    <p>Como administrador, você pode cadastrar novos funcionários no sistema. Use o menu de navegação para acessar a página de cadastro.</p>
                </section>
            <?php } ?>
        </div>
    </main>
</body>
</html>