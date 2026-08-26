<?php
require_once __DIR__ . "/../conexao/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../cadastro.php");
    exit;
}

$nome = trim($_POST["nome"] ?? "");
$cpf = trim($_POST["cpf"] ?? "");
$telefone = trim($_POST["telefone"] ?? "");
$email = trim($_POST["email"] ?? "");
$senha = $_POST["senha"] ?? "";
$confirmar = $_POST["confirmar_senha"] ?? "";

if ($nome === "" || $cpf === "" || $email === "" || $senha === "") {
    header("Location: ../cadastro.php?erro=" . urlencode("Preencha todos os campos obrigatórios."));
    exit;
}

if ($senha !== $confirmar) {
    header("Location: ../cadastro.php?erro=" . urlencode("As senhas não coincidem."));
    exit;
}

$consulta = mysqli_prepare(
    $conexao,
    "SELECT id_usuario FROM usuarios WHERE email = ? OR cpf = ? LIMIT 1"
);
mysqli_stmt_bind_param($consulta, "ss", $email, $cpf);
mysqli_stmt_execute($consulta);
$resultado = mysqli_stmt_get_result($consulta);

if (mysqli_num_rows($resultado) > 0) {
    header("Location: ../cadastro.php?erro=" . urlencode("E-mail ou CPF já cadastrado."));
    exit;
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);
$tipo = "Paciente";

$insert = mysqli_prepare(
    $conexao,
    "INSERT INTO usuarios (nome, cpf, telefone, email, senha, tipo_usuario)
     VALUES (?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param($insert, "ssssss", $nome, $cpf, $telefone, $email, $senhaHash, $tipo);

if (mysqli_stmt_execute($insert)) {
    header("Location: ../login.php?sucesso=" . urlencode("Cadastro realizado com sucesso."));
    exit;
}

header("Location: ../cadastro.php?erro=" . urlencode("Não foi possível realizar o cadastro."));
exit;
?>
