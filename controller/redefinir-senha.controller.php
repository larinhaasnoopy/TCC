<?php
session_start();
require_once __DIR__ . "/../conexao/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../esqueci-senha.php");
    exit;
}

$token = $_POST["token"] ?? "";
$senha = $_POST["senha"] ?? "";
$confirmar = $_POST["confirmar_senha"] ?? "";

$redirecionarErro = function (string $mensagem) use ($token) {
    header("Location: ../redefinir-senha.php?token=" . urlencode($token) . "&erro=" . urlencode($mensagem));
    exit;
};

if ($senha === "" || strlen($senha) < 6) {
    $redirecionarErro("A senha deve ter pelo menos 6 caracteres.");
}

if ($senha !== $confirmar) {
    $redirecionarErro("As senhas não coincidem.");
}

$stmt = $pdo->prepare(
    "SELECT * FROM redefinicao_senha
     WHERE token = :token AND usado = 0 AND expira_em >= NOW()
     LIMIT 1"
);
$stmt->execute(["token" => $token]);
$redefinicao = $stmt->fetch();

if (!$redefinicao) {
    header("Location: ../esqueci-senha.php?erro=" . urlencode("Este link é inválido ou já expirou."));
    exit;
}

$tabelasPorTipo = [
    "usuario" => ["tabela" => "usuario", "coluna_email" => "email"],
    "medico" => ["tabela" => "medico", "coluna_email" => "email"],
    "administrador" => ["tabela" => "administrador", "coluna_email" => "email"],
];

$info = $tabelasPorTipo[$redefinicao["tipo_conta"]] ?? null;

if (!$info) {
    header("Location: ../esqueci-senha.php?erro=" . urlencode("Não foi possível redefinir a senha."));
    exit;
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

try {
    $pdo->beginTransaction();

    $update = $pdo->prepare(
        "UPDATE {$info['tabela']} SET senha = :senha WHERE {$info['coluna_email']} = :email"
    );
    $update->execute(["senha" => $senhaHash, "email" => $redefinicao["email"]]);

    $marcarUsado = $pdo->prepare("UPDATE redefinicao_senha SET usado = 1 WHERE id_redefinicao = :id");
    $marcarUsado->execute(["id" => $redefinicao["id_redefinicao"]]);

    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    $redirecionarErro("Não foi possível redefinir a senha. Tente novamente.");
}

header("Location: ../login.php?sucesso=" . urlencode("Senha redefinida com sucesso! Faça login com a nova senha."));
exit;
