<?php

$host = "localhost";
$dbname = "agenda_saude";
$usuario = "root";
$senha = "";

$dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

$opcoesPdo = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $usuario, $senha, $opcoesPdo);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
