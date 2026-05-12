<?php
// Subindo duas pastas para encontrar o app/bootstrap.php
require_once __DIR__ . '/../../../app/bootstrap.php';

use App\Providers\AuthProvider;
use App\Middlewares\AuthMiddleware;

AuthMiddleware::requireLogin();
$user = AuthProvider::user();

// Se a página não definir um título, usamos um padrão
if (!isset($titulo_pagina)) {
    $titulo_pagina = 'Sistema Escolar';
}

// Se a página não definir qual menu está ativo, deixamos vazio
if (!isset($pagina_ativa)) {
    $pagina_ativa = '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo_pagina); ?></title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="resources/css/painel.css?v=<?php echo time(); ?>">
</head>

<body>

<div class="layout" id="layout">
    
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-area">
                <i class="fa-solid fa-graduation-cap"></i>
                <img src="img/logo_nome.png" alt="Logo Conect Inove" class="logo">
            </div>
            <button class="toggle-btn" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <nav class="sidebar-menu">
            <a href="painel.php" class="menu-link <?php echo ($pagina_ativa == 'home') ? 'active' : ''; ?>">
                <i class="fa-solid fa-house"></i> 
                <span class="menu-text">Home</span>
            </a>
            <a href="matricula.php" class="menu-link <?php echo ($pagina_ativa == 'matricula') ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-plus"></i> 
                <span class="menu-text">Matrícula</span>
            </a>
            <?php if (($user['tipo'] ?? '') === 'admin') { ?>
                <a href="funcionario.php" class="menu-link <?php echo ($pagina_ativa == 'funcionarios') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-users-gear"></i> 
                    <span class="menu-text">Funcionários</span>
                </a>
            <?php } ?>
                 <a href="aluno.php" class="menu-link <?php echo ($pagina_ativa == 'aluno') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user-graduate"></i>
                    <span class="menu-text">Aluno</span>
            </a>
             <?php if (($user['tipo'] ?? '') === 'admin') { ?>
                 <a href="" class="menu-link <?php echo ($pagina_ativa == 'dashboard') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-line"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
                <?php } ?>
            <?php if (($user['tipo'] ?? '') === 'admin') { ?>
                    <a href="" class="menu-link <?php echo ($pagina_ativa == 'cursos') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-book"></i>
                    <span class="menu-text">Cursos</span>
                </a>
                <?php } ?>

            <a href="" class="menu-link <?php echo ($pagina_ativa == 'pagamento') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    <span class="menu-text">Pagamento</span>
             </a>
        </nav>

        <div class="sidebar-footer">
            <a href="logout.php" class="logout-btn">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> 
                <span class="menu-text">Sair da Conta</span>
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="content-body">