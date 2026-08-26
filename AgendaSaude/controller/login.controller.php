<?php
session_start();
require_once __DIR__ . "/../conexao/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit;
}

$email = trim($_POST["email"] ?? "");
$senha = $_POST["senha"] ?? "";

if ($email === "" || $senha === "") {
    header("Location: ../login.php?erro=" . urlencode("Informe e-mail e senha."));
    exit;
}

$stmt = mysqli_prepare(
    $conexao,
    "SELECT id_usuario, nome, email, senha, tipo_usuario
     FROM usuarios
     WHERE email = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

if (!$usuario) {
    header("Location: ../login.php?erro=" . urlencode("E-mail ou senha inválidos."));
    exit;
}

/*
 * Compatibilidade com o usuário de teste antigo do banco.
 * Usuários cadastrados pelo sistema usam password_hash().
 */
$senhaValida = password_verify($senha, $usuario["senha"]) || hash_equals((string)$usuario["senha"], (string)$senha);

if (!$senhaValida) {
    header("Location: ../login.php?erro=" . urlencode("E-mail ou senha inválidos."));
    exit;
}

/* Se a senha antiga estava em texto puro, atualiza para hash. */
if (!password_verify($senha, $usuario["senha"])) {
    $novoHash = password_hash($senha, PASSWORD_DEFAULT);
    $update = mysqli_prepare($conexao, "UPDATE usuarios SET senha = ? WHERE id_usuario = ?");
    mysqli_stmt_bind_param($update, "si", $novoHash, $usuario["id_usuario"]);
    mysqli_stmt_execute($update);
}

session_regenerate_id(true);

$_SESSION["id_usuario"] = $usuario["id_usuario"];
$_SESSION["nome_usuario"] = $usuario["nome"];
$_SESSION["email_usuario"] = $usuario["email"];
$_SESSION["tipo_usuario"] = $usuario["tipo_usuario"];

if ($usuario["tipo_usuario"] === "Administrador") {
    header("Location: ../admin/dashboard.php");
} else {
    header("Location: ../paciente/dashboard.php");
}
exit;
?>
