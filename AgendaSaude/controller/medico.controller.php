<?php
session_start();
require_once __DIR__ . "/../conexao/conexao.php";

if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo_usuario"] !== "Administrador") {
    header("Location: ../login.php?erro=" . urlencode("Acesso não autorizado."));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin/medicos.php");
    exit;
}

$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$cpf = trim($_POST["cpf"] ?? "");
$telefone = trim($_POST["telefone"] ?? "");
$crm = trim($_POST["crm"] ?? "");
$idEspecialidade = (int)($_POST["id_especialidade"] ?? 0);
$idUnidade = (int)($_POST["id_unidade"] ?? 0);

if ($nome === "" || $email === "" || $cpf === "" || $crm === "" || $idEspecialidade <= 0 || $idUnidade <= 0) {
    header("Location: ../admin/medicos.php?erro=" . urlencode("Preencha todos os campos."));
    exit;
}

$senhaInicial = password_hash("123456", PASSWORD_DEFAULT);

mysqli_begin_transaction($conexao);

try {
    $user = mysqli_prepare(
        $conexao,
        "INSERT INTO usuarios (nome, cpf, telefone, email, senha, tipo_usuario)
         VALUES (?, ?, ?, ?, ?, 'Medico')"
    );
    mysqli_stmt_bind_param($user, "sssss", $nome, $cpf, $telefone, $email, $senhaInicial);
    mysqli_stmt_execute($user);

    $idUsuario = mysqli_insert_id($conexao);

    $medico = mysqli_prepare(
        $conexao,
        "INSERT INTO medicos (id_usuario, id_especialidade, id_unidade, crm)
         VALUES (?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($medico, "iiis", $idUsuario, $idEspecialidade, $idUnidade, $crm);
    mysqli_stmt_execute($medico);

    mysqli_commit($conexao);
    header("Location: ../admin/medicos.php?sucesso=" . urlencode("Médico cadastrado. Senha inicial: 123456"));
    exit;
} catch (Throwable $e) {
    mysqli_rollback($conexao);
    header("Location: ../admin/medicos.php?erro=" . urlencode("Não foi possível cadastrar o médico."));
    exit;
}
?>
