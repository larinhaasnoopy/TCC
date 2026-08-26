<?php
session_start();
require_once __DIR__ . "/../conexao/conexao.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php?erro=" . urlencode("Faça login para continuar."));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../paciente/agendar.php");
    exit;
}

$idUsuario = (int)$_SESSION["id_usuario"];
$idMedico = (int)($_POST["id_medico"] ?? 0);
$idHorario = (int)($_POST["id_horario"] ?? 0);
$motivo = trim($_POST["motivo"] ?? "");

if ($idMedico <= 0 || $idHorario <= 0) {
    header("Location: ../paciente/agendar.php?erro=" . urlencode("Selecione médico e horário."));
    exit;
}

$verifica = mysqli_prepare(
    $conexao,
    "SELECT id_horario FROM horarios
     WHERE id_horario = ? AND id_medico = ? AND disponivel = 1
     LIMIT 1"
);
mysqli_stmt_bind_param($verifica, "ii", $idHorario, $idMedico);
mysqli_stmt_execute($verifica);
$horario = mysqli_stmt_get_result($verifica);

if (mysqli_num_rows($horario) === 0) {
    header("Location: ../paciente/agendar.php?erro=" . urlencode("Esse horário não está mais disponível."));
    exit;
}

mysqli_begin_transaction($conexao);

try {
    $insert = mysqli_prepare(
        $conexao,
        "INSERT INTO agendamentos
        (id_usuario, id_medico, id_horario, motivo, status)
        VALUES (?, ?, ?, ?, 'Agendada')"
    );
    mysqli_stmt_bind_param($insert, "iiis", $idUsuario, $idMedico, $idHorario, $motivo);
    mysqli_stmt_execute($insert);

    $update = mysqli_prepare(
        $conexao,
        "UPDATE horarios SET disponivel = 0 WHERE id_horario = ?"
    );
    mysqli_stmt_bind_param($update, "i", $idHorario);
    mysqli_stmt_execute($update);

    mysqli_commit($conexao);

    header("Location: ../paciente/minhas_consultas.php?sucesso=" . urlencode("Consulta agendada com sucesso."));
    exit;
} catch (Throwable $e) {
    mysqli_rollback($conexao);
    header("Location: ../paciente/agendar.php?erro=" . urlencode("Não foi possível realizar o agendamento."));
    exit;
}
?>
