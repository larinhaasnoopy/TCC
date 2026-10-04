<?php
session_start();
require_once __DIR__ . "/../conexao/conexao.php";

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["tipo_usuario"] ?? "") !== "Paciente") {
    header("Location: ../login.php?erro=" . urlencode("Faça login para continuar."));
    exit;
}

$idAgendamento = (int)($_POST["id_agendamento"] ?? 0);
$idUsuario = (int)$_SESSION["id_usuario"];
$voltarPara = $_POST["voltar_para"] ?? "../index.php";

/* só permite mexer no próprio agendamento */
$stmt = $pdo->prepare(
    "SELECT lembrete_ativo FROM agendamento WHERE id_agendamento = :id AND id_usuario = :id_usuario"
);
$stmt->execute(["id" => $idAgendamento, "id_usuario" => $idUsuario]);
$agendamento = $stmt->fetch();

if ($agendamento) {
    $novoValor = $agendamento["lembrete_ativo"] ? 0 : 1;

    $update = $pdo->prepare(
        "UPDATE agendamento SET lembrete_ativo = :valor WHERE id_agendamento = :id AND id_usuario = :id_usuario"
    );
    $update->execute(["valor" => $novoValor, "id" => $idAgendamento, "id_usuario" => $idUsuario]);

    $mensagem = $novoValor
        ? "Lembrete ativado! Você será avisado 1 dia antes da consulta."
        : "Lembrete desativado.";

    header("Location: " . $voltarPara . "?sucesso=" . urlencode($mensagem));
    exit;
}

header("Location: " . $voltarPara . "?erro=" . urlencode("Não foi possível atualizar o lembrete."));
exit;
