<?php
$titulo_pagina = "Cadastrar Funcionário - Conect Inove";
$pagina_ativa = "funcionarios"; 

include 'resources/layout/header.php';
?>

<div style="max-width: 550px; margin: 40px auto 0 auto;">

    <section class="welcome-section" style="text-align: center; margin-bottom: 30px;">
        <h1>Cadastrar Funcionário</h1>
        <p class="subtitle">Defina o nível de acesso e as credenciais da conta.</p>
    </section>

    <div class="panel-form" style="margin: 0;">
        <form id="formCadastro">
            
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-signature"></i>
                    <input type="text" id="nome" name="nome" placeholder="Digite o nome completo" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">E-mail Corporativo</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="email@escola.com" required autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="tipo">Cargo / Nível de Acesso</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-user-shield"></i>
                    <select id="tipo" name="tipo" required>
                        <option value="" disabled selected>Escolha uma função...</option>
                        <option value="funcionario">Funcionário (Acesso Limitado)</option>
                        <option value="admin">Administrador (Acesso Total)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="senha">Senha de Acesso</label>
                <div class="input-with-icon">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="senha" name="senha" placeholder="Mínimo de 6 caracteres" required autocomplete="new-password">
                </div>
            </div>

            <div id="mensagemRetorno" class="msg-alert"></div>

            <div style="margin-top: 10px;">
                <button type="submit" class="btn btn-primary btn-save">
                    <i class="fa-solid fa-user-plus"></i> Finalizar Cadastro
                </button>
                
                <a href="funcionario.php" class="btn btn-secondary btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Voltar para a Lista
                </a>
            </div>

        </form>
    </div>

</div>

<?php include 'resources/layout/footer.php'; ?>