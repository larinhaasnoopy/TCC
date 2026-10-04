<?php

session_start();

require_once __DIR__ . "/../conexao/conexao.php";

if (
    !isset($_SESSION["id_medico"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Medico"
) {
    header(
        "Location: ../login.php?erro=" .
        urlencode("Acesso não autorizado.")
    );
    exit;
}

$idMedico = (int) $_SESSION["id_medico"];
$idAgendamento = (int) ($_POST["id_agendamento"] ?? 0);
$acao = $_POST["acao"] ?? "";
$dataFiltro = $_POST["data_filtro"] ?? date("Y-m-d");

$acoes = [
    "iniciar" => "em_atendimento",
    "concluir" => "concluido"
];

if (
    $idAgendamento <= 0 ||
    !isset($acoes[$acao])
) {
    header(
        "Location: ../medico/dashboard.php?data=" .
        urlencode($dataFiltro) .
        "&erro=" .
        urlencode("Ação inválida.")
    );
    exit;
}

/* Verifica a consulta */
$stmt = $pdo->prepare("
    SELECT ag.id_agendamento
    FROM agendamento ag
    INNER JOIN agenda a
        ON a.id_agenda = ag.id_agenda
    WHERE ag.id_agendamento = :id
      AND a.id_medico = :id_medico
    LIMIT 1
");

$stmt->execute([
    "id" => $idAgendamento,
    "id_medico" => $idMedico
]);

if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
    header(
        "Location: ../medico/dashboard.php?data=" .
        urlencode($dataFiltro) .
        "&erro=" .
        urlencode("Consulta não encontrada.")
    );
    exit;
}

$novoStatus = $acoes[$acao];

$update = $pdo->prepare("
    UPDATE agendamento
    SET status = :status
    WHERE id_agendamento = :id
");

$update->execute([
    "status" => $novoStatus,
    "id" => $idAgendamento
]);

header(
    "Location: ../medico/dashboard.php?data=" .
    urlencode($dataFiltro) .
    "&sucesso=" .
    urlencode("Consulta atualizada com sucesso.")
);

exit;