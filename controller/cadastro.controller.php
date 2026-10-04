<?php
session_start();

require_once __DIR__ . "/../conexao/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../cadastro.php");
    exit;
}

$tipo = strtolower(trim($_POST["tipo"] ?? ""));

$tiposValidos = [
    "paciente",
    "medico",
    "administrador"
];

if (!in_array($tipo, $tiposValidos, true)) {
    header("Location: ../cadastro.php?erro=" . urlencode("Tipo de cadastro inválido."));
    exit;
}

function erroCadastro(string $mensagem, string $tipo): void
{
    $dados = $_POST;

    unset(
        $dados["senha"],
        $dados["confirmar_senha"],
        $dados["senha_confirmacao"]
    );

    $_SESSION["old_cadastro"] = $dados;

    header(
        "Location: ../cadastro.php?tipo=" .
        urlencode($tipo) .
        "&erro=" .
        urlencode($mensagem)
    );

    exit;
}

$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$senha = $_POST["senha"] ?? "";
$confirmarSenha = $_POST["confirmar_senha"] ?? "";

if ($nome === "" || $email === "" || $senha === "" || $confirmarSenha === "") {
    erroCadastro("Preencha todos os campos obrigatórios.", $tipo);
}

if (!preg_match("/^[A-Za-zÀ-ÿ\s]+$/u", $nome)) {
    erroCadastro("O nome deve conter apenas letras e espaços.", $tipo);
}

if (strlen($nome) > 100) {
    erroCadastro("O nome deve ter no máximo 100 caracteres.", $tipo);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    erroCadastro("Informe um e-mail válido.", $tipo);
}

if (strlen($email) > 150) {
    erroCadastro("O e-mail deve ter no máximo 150 caracteres.", $tipo);
}

if (strlen($senha) < 6) {
    erroCadastro("A senha deve ter pelo menos 6 caracteres.", $tipo);
}

if ($senha !== $confirmarSenha) {
    erroCadastro("As senhas não coincidem.", $tipo);
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

/* Paciente */
if ($tipo === "paciente") {

    $cpf = preg_replace("/\D/", "", $_POST["cpf"] ?? "");
    $telefone = preg_replace("/\D/", "", $_POST["telefone"] ?? "");

    if (strlen($cpf) !== 11) {
        erroCadastro("O CPF deve conter exatamente 11 números.", $tipo);
    }

    if (strlen($telefone) !== 11) {
        erroCadastro("O telefone deve conter 11 números.", $tipo);
    }

    $stmt = $pdo->prepare("
        SELECT id_usuario
        FROM usuario
        WHERE cpf = :cpf
        LIMIT 1
    ");

    $stmt->execute([
        "cpf" => $cpf
    ]);

    if ($stmt->fetch()) {
        erroCadastro("CPF já existe.", $tipo);
    }

    $stmt = $pdo->prepare("
        SELECT id_usuario
        FROM usuario
        WHERE email = :email
        LIMIT 1
    ");

    $stmt->execute([
        "email" => $email
    ]);

    if ($stmt->fetch()) {
        erroCadastro("Este e-mail já está cadastrado.", $tipo);
    }

    $stmt = $pdo->prepare("
        INSERT INTO usuario (
            nome,
            cpf,
            email,
            senha,
            telefone
        )
        VALUES (
            :nome,
            :cpf,
            :email,
            :senha,
            :telefone
        )
    ");

    $stmt->execute([
        "nome" => $nome,
        "cpf" => $cpf,
        "email" => $email,
        "senha" => $senhaHash,
        "telefone" => $telefone
    ]);

    unset($_SESSION["old_cadastro"]);

    header(
        "Location: ../login.php?sucesso=" .
        urlencode("Cadastro realizado com sucesso. Faça login para continuar.")
    );

    exit;
}

/* Médico */
if ($tipo === "medico") {

    $crm = preg_replace("/\D/", "", $_POST["crm"] ?? "");
    $ufCrm = strtoupper(trim($_POST["uf_crm"] ?? ""));
    $idEspecialidade = (int) ($_POST["id_especialidade"] ?? 0);

    $ufsValidas = [
        "AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO",
        "MA", "MT", "MS", "MG", "PA", "PB", "PR", "PE", "PI",
        "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"
    ];

    if (!preg_match("/^\d{5}$/", $crm)) {
        erroCadastro("O CRM deve conter exatamente 5 números.", $tipo);
    }

    if (!in_array($ufCrm, $ufsValidas, true)) {
        erroCadastro("UF do CRM inválida.", $tipo);
    }

    if ($idEspecialidade <= 0) {
        erroCadastro("Selecione uma especialidade.", $tipo);
    }

    $stmt = $pdo->prepare("
        SELECT id_especialidade
        FROM especialidade_medica
        WHERE id_especialidade = :id
        LIMIT 1
    ");

    $stmt->execute([
        "id" => $idEspecialidade
    ]);

    if (!$stmt->fetch()) {
        erroCadastro("Especialidade não encontrada.", $tipo);
    }

    $stmt = $pdo->prepare("
        SELECT id_medico
        FROM medico
        WHERE crm = :crm
        LIMIT 1
    ");

    $stmt->execute([
        "crm" => $crm
    ]);

    if ($stmt->fetch()) {
        erroCadastro("Este CRM já está cadastrado.", $tipo);
    }

    $stmt = $pdo->prepare("
        SELECT id_medico
        FROM medico
        WHERE email = :email
        LIMIT 1
    ");

    $stmt->execute([
        "email" => $email
    ]);

    if ($stmt->fetch()) {
        erroCadastro("Este e-mail já está cadastrado para um médico.", $tipo);
    }

    $stmt = $pdo->prepare("
        INSERT INTO medico (
            nome,
            crm,
            uf_crm,
            email,
            senha,
            id_especialidade
        )
        VALUES (
            :nome,
            :crm,
            :uf_crm,
            :email,
            :senha,
            :id_especialidade
        )
    ");

    $stmt->execute([
        "nome" => $nome,
        "crm" => $crm,
        "uf_crm" => $ufCrm,
        "email" => $email,
        "senha" => $senhaHash,
        "id_especialidade" => $idEspecialidade
    ]);

    unset($_SESSION["old_cadastro"]);

    header(
        "Location: ../login.php?sucesso=" .
        urlencode("Cadastro de médico realizado com sucesso. Faça login para continuar.")
    );

    exit;
}

/* Administrador */
if ($tipo === "administrador") {

    $stmt = $pdo->prepare("
        SELECT id_administrador
        FROM administrador
        WHERE email = :email
        LIMIT 1
    ");

    $stmt->execute([
        "email" => $email
    ]);

    if ($stmt->fetch()) {
        erroCadastro("Este e-mail já está cadastrado para um administrador.", $tipo);
    }

    $stmt = $pdo->prepare("
        INSERT INTO administrador (
            nome,
            email,
            senha
        )
        VALUES (
            :nome,
            :email,
            :senha
        )
    ");

    $stmt->execute([
        "nome" => $nome,
        "email" => $email,
        "senha" => $senhaHash
    ]);

    unset($_SESSION["old_cadastro"]);

    header(
        "Location: ../login.php?sucesso=" .
        urlencode("Cadastro de administrador realizado com sucesso. Faça login para continuar.")
    );

    exit;
}