<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo "❌ Erro na conexão: " . $conn->connect_error;
} else {
    echo "✅ Conectado com sucesso ao banco de dados!";
}

$conn->close();
?>
