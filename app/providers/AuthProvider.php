<?php

namespace App\Providers;

use App\Providers\DatabaseProvider; // <-- Importamos a conexão direta com o banco

class AuthProvider
{
    public static function startSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        \noCache();
    }

    public static function attempt(string $email, string $senha): bool
    {
        self::startSession();

        $conn = DatabaseProvider::connect();

        // 1. Busca o usuário apenas pelo E-MAIL
        $stmt = $conn->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Se o usuário não for encontrado no banco
        if (!$user) {
            return false;
        }

        // 2. TRAVA DE SEGURANÇA: Bloqueia se o status for inativo
        if (($user['status'] ?? 'ativo') === 'inativo') {
            return false;
        }

        // 3. VERIFICAÇÃO DE SENHA CRIPTOGRAFADA
        // Compara a senha digitada com o "Hash" gigante salvo no banco
        if (!password_verify($senha, $user['senha'])) {
            return false; // Senha incorreta
        }

        // 4. SUCESSO! Atualiza a data do último login
        $userId = $user['id'];
        $conn->query("UPDATE usuario SET ultimo_login = NOW() WHERE id = $userId");

        // 5. Cria a sessão do usuário
        $_SESSION['id_usuario'] = $user['id'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['tipo'] = $user['tipo'];

        return true;
    }

    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        session_unset();
        session_destroy();
        \noCache();
    }

    public static function check(): bool
    {
        self::startSession();
        return isset($_SESSION['id_usuario']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return [
            'id' => $_SESSION['id_usuario'],
            'nome' => $_SESSION['nome'] ?? '',
            'tipo' => $_SESSION['tipo'] ?? '',
        ];
    }
}