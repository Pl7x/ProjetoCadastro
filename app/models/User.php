<?php

namespace App\Models;

use App\Providers\DatabaseProvider;

class User
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
}
