<?php
session_start();
require_once __DIR__ . "/../conexao/conexao.php";

if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo_usuario"] !== "Administrador") {
    header("Location: ../login.php?erro=" . urlencode("Acesso não autorizado."));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin/unidades.php");
    exit;
}

$nome = trim($_POST["nome_unidade"] ?? "");
$endereco = trim($_POST["endereco"] ?? "");
$telefone = trim($_POST["telefone"] ?? "");
$cidade = trim($_POST["cidade"] ?? "");
$latitude = $_POST["latitude"] !== "" ? (float)$_POST["latitude"] : null;
$longitude = $_POST["longitude"] !== "" ? (float)$_POST["longitude"] : null;

if ($nome === "" || $endereco === "" || $cidade === "") {
    header("Location: ../admin/unidades.php?erro=" . urlencode("Preencha nome, endereço e cidade."));
    exit;
}

$stmt = mysqli_prepare(
    $conexao,
    "INSERT INTO unidades_saude
    (nome_unidade, endereco, telefone, cidade, latitude, longitude)
    VALUES (?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param($stmt, "ssssdd", $nome, $endereco, $telefone, $cidade, $latitude, $longitude);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../admin/unidades.php?sucesso=" . urlencode("Unidade cadastrada com sucesso."));
    exit;
}

header("Location: ../admin/unidades.php?erro=" . urlencode("Não foi possível cadastrar a unidade."));
exit;
?>
