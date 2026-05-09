<?php
// Configurações exclusivas desta página
$titulo_pagina = "Home - Conect Inove";
$pagina_ativa = "home";

// Chama o cabeçalho passando o caminho correto da pasta layout
include 'resources/layout/header.php';
?>

<section class="welcome-section">
    <h1>Bem-vindo, <?php echo htmlspecialchars($user['nome'] ?? 'Usuário', ENT_QUOTES, 'UTF-8'); ?>!</h1>
    
    <div id="relogio" class="local-time">
        <i class="fa-regular fa-clock"></i> Carregando horário...
    </div>

    <p class="subtitle">Você está logado como <span class="highlight-blue"><?php echo htmlspecialchars($user['tipo'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></span></p>
</section>



<?php 
// Chama o rodapé para fechar a página
include 'resources/layout/footer.php'; 
?>