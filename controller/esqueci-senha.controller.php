<?php
session_start();
require_once __DIR__ . "/../conexao/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../esqueci-senha.php");
    exit;
}

$email = trim($_POST["email"] ?? "");

if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../esqueci-senha.php?erro=" . urlencode("Informe um e-mail válido."));
    exit;
}

/* Procura o e-mail nas 3 tabelas, nessa ordem */
$tabelas = [
    "administrador" => "administrador",
    "medico" => "medico",
    "usuario" => "usuario",
];

$tipoEncontrado = null;

foreach ($tabelas as $tipoConta => $tabela) {
    $stmt = $pdo->prepare("SELECT 1 FROM $tabela WHERE email = :email LIMIT 1");
    $stmt->execute(["email" => $email]);
    if ($stmt->fetch()) {
        $tipoEncontrado = $tipoConta;
        break;
    }
}

if (!$tipoEncontrado) {
    header("Location: ../esqueci-senha.php?erro=" . urlencode("Não encontramos conta com esse e-mail."));
    exit;
}

$token = bin2hex(random_bytes(32));
$expiraEm = date("Y-m-d H:i:s", strtotime("+1 hour"));

$insert = $pdo->prepare(
    "INSERT INTO redefinicao_senha (email, tipo_conta, token, expira_em)
     VALUES (:email, :tipo_conta, :token, :expira_em)"
);
$insert->execute([
    "email" => $email,
    "tipo_conta" => $tipoEncontrado,
    "token" => $token,
    "expira_em" => $expiraEm,
]);

/*
 * Em um ambiente com e-mail configurado, o link abaixo seria enviado
 * por e-mail em vez de exibido na tela. Como é um projeto local no
 * XAMPP, mostramos o link diretamente para o usuário poder testar.
 */
$_SESSION["link_redefinicao"] = "redefinir-senha.php?token=" . $token;

header("Location: ../esqueci-senha.php");
exit;
