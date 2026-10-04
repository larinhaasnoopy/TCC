<?php
session_start();
require_once __DIR__ . "/../conexao/conexao.php";

if (!isset($_SESSION["id_usuario"]) || ($_SESSION["tipo_usuario"] ?? "") !== "Paciente") {
    header("Location: ../login.php?erro=" . urlencode("Faça login para continuar."));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../paciente/agendar.php");
    exit;
}

$idUsuario = (int)$_SESSION["id_usuario"];
$idAgenda = (int)($_POST["id_agenda"] ?? 0);

if ($idAgenda <= 0) {
    header("Location: ../paciente/agendar.php?erro=" . urlencode("Selecione um horário."));
    exit;
}

/* Confirma que o horário existe e ainda está disponível */
$verifica = $pdo->prepare(
    "SELECT id_agenda, data, horario_disponivel
     FROM agenda
     WHERE id_agenda = :id_agenda AND status_horario = 'disponivel'
     LIMIT 1"
);
$verifica->execute(["id_agenda" => $idAgenda]);
$agenda = $verifica->fetch();

if (!$agenda) {
    header("Location: ../paciente/agendar.php?erro=" . urlencode("Esse horário não está mais disponível."));
    exit;
}

try {
    $pdo->beginTransaction();

    $insert = $pdo->prepare(
        "INSERT INTO agendamento (data_agendamento, horario, status, id_usuario, id_agenda)
         VALUES (:data, :horario, 'pendente', :id_usuario, :id_agenda)"
    );
    $insert->execute([
        "data" => $agenda["data"],
        "horario" => $agenda["horario_disponivel"],
        "id_usuario" => $idUsuario,
        "id_agenda" => $idAgenda,
    ]);

    $update = $pdo->prepare(
        "UPDATE agenda SET status_horario = 'reservado' WHERE id_agenda = :id_agenda"
    );
    $update->execute(["id_agenda" => $idAgenda]);

    $pdo->commit();

    header("Location: ../paciente/minhas_consultas.php?sucesso=" . urlencode("Consulta agendada com sucesso."));
    exit;
} catch (Throwable $e) {
    $pdo->rollBack();
    header("Location: ../paciente/agendar.php?erro=" . urlencode("Não foi possível realizar o agendamento."));
    exit;
}
