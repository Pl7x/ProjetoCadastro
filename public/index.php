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

        <form id="loginForm" method="POST" action="login.php">
            <div class="input-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" placeholder="Digite seu email" autocomplete="email" required>
            </div>

            <div class="input-group">
                <label for="senha">Senha</label>
                <input id="senha" type="password" name="senha" placeholder="Digite sua senha" autocomplete="current-password" required>
            </div>

            <button type="submit" id="submitBtn">Entrar</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const emailInput = document.getElementById('email');
    const senhaInput = document.getElementById('senha');

    // Remover mensagem de erro existente se houver
    const existingError = document.querySelector('.erro');
    if (existingError) {
        existingError.remove();
    }

    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = emailInput.value.trim();
        const senha = senhaInput.value.trim();

        // Validação básica no frontend
        if (!email || !senha) {
            showError('Por favor, preencha todos os campos');
            return;
        }

        if (!isValidEmail(email)) {
            showError('Por favor, digite um email válido');
            return;
        }

        // Desabilitar botão durante o processamento
        submitBtn.disabled = true;
        submitBtn.textContent = 'Entrando...';

        try {
            const response = await fetch('api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    email: email,
                    senha: senha
                })
            });

            const data = await response.json();

            if (data.success) {
                // Login bem-sucedido - redirecionar
                window.location.href = data.redirect;
            } else {
                // Mostrar erro
                showError(data.message);
            }
        } catch (error) {
            console.error('Erro:', error);
            showError('Erro ao conectar com o servidor. Tente novamente.');
        } finally {
            // Reabilitar botão
            submitBtn.disabled = false;
            submitBtn.textContent = 'Entrar';
        }
    });

    function showError(message) {
        // Remover erro existente
        const existingError = document.querySelector('.erro');
        if (existingError) {
            existingError.remove();
        }

        // Criar novo elemento de erro
        const errorDiv = document.createElement('div');
        errorDiv.className = 'erro';
        errorDiv.textContent = message;

        // Inserir antes do formulário
        const form = document.getElementById('loginForm');
        form.parentNode.insertBefore(errorDiv, form);

        // Focar no campo apropriado
        if (message.includes('email')) {
            emailInput.focus();
        } else if (message.includes('senha')) {
            senhaInput.focus();
        }
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Limpar erros quando o usuário começar a digitar
    emailInput.addEventListener('input', clearErrors);
    senhaInput.addEventListener('input', clearErrors);

    function clearErrors() {
        const error = document.querySelector('.erro');
        if (error) {
            error.remove();
        }
    }
});
</script>

</body>
</html>