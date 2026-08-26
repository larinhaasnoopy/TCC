<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "agenda_saude";

$conexao = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conexao) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

mysqli_set_charset($conexao, "utf8mb4");
?>