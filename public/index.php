<?php
require_once __DIR__ . '/../app/bootstrap.php';

use App\Providers\AuthProvider;

AuthProvider::startSession();

if (AuthProvider::check()) {
    redirect('painel.php');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Escolar</title>
    <link rel="stylesheet" href="resources/css/login.css?v=<?php echo time(); ?>">
</head>

<body>

<div class="container">

    <div class="login-card">
        
        <div class="login-header">
            <img src="img/logo_login.png" alt="Logo Conect Inove" class="logo">
            <h2>Login</h2>
            <p>Acesse o sistema escolar</p>
        </div>

        <div id="mensagemErro" class="erro" style="display:none;"></div>

        <form id="loginForm">

            <div class="input-group">
                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    placeholder="Digite seu email"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="input-group">
                <label for="senha">Senha</label>
                <input
                    id="senha"
                    type="password"
                    name="senha"
                    placeholder="Digite sua senha"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" id="submitBtn">
                Entrar
            </button>

        </form>

    </div>

</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e){
    e.preventDefault();

    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;
    const botao = document.getElementById('submitBtn');
    const erro = document.getElementById('mensagemErro');

    erro.style.display = 'none';
    erro.innerText = '';

    botao.disabled = true;
    botao.innerText = 'Entrando...';

    try {
        const response = await fetch('api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                email: email,
                senha: senha
            })
        });

        const data = await response.json();

        if(data.success){
            window.location.href = data.redirect;
        } else {
            erro.style.display = 'block';
            erro.innerText = data.message;
        }

    } catch(error){
        console.error(error);
        erro.style.display = 'block';
        erro.innerText = 'Erro ao conectar com o servidor';
    }

    botao.disabled = false;
    botao.innerText = 'Entrar';
});
</script>

</body>
</html>