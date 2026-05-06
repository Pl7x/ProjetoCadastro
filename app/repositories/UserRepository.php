<?php

namespace App\Repositories;

use App\Providers\DatabaseProvider;

class UserRepository
{
    public static function findByEmailAndPassword(string $email, string $senha)
    {
        $db = DatabaseProvider::connect();
        $email = $db->real_escape_string($email);
        $senha = $db->real_escape_string($senha);

        $query = "SELECT * FROM usuario WHERE email='{$email}' AND senha='{$senha}' LIMIT 1";
        $result = $db->query($query);

        if ($result && $result->num_rows === 1) {
            return $result->fetch_assoc();
        }

        return null;
    }

    public static function createEmployee(string $nome, string $email, string $senha): bool
    {
        $db = DatabaseProvider::connect();
        $nome = $db->real_escape_string($nome);
        $email = $db->real_escape_string($email);
        $senha = $db->real_escape_string($senha);

        $query = "INSERT INTO usuario (nome, email, senha, tipo) VALUES ('{$nome}', '{$email}', '{$senha}', 'funcionario')";
        return $db->query($query);
    }
}
