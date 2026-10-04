<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

if (
    !isset($_SESSION["id_usuario"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Paciente"
) {
    http_response_code(403);

    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Acesso não autorizado."
    ]);

    exit;
}

require_once __DIR__ . "/../conexao/conexao.php";

$idMedico = (int) ($_GET["id_medico"] ?? 0);
$idUnidade = (int) ($_GET["id_unidade"] ?? 0);
$data = trim($_GET["data"] ?? "");

if ($idMedico <= 0 || $idUnidade <= 0) {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Médico ou unidade inválidos."
    ]);

    exit;
}

/*
 * Retorna as datas.
 */
if ($data === "") {

    $stmt = $pdo->prepare("
        SELECT DISTINCT
            ag.data
        FROM agenda ag

        INNER JOIN medico m
            ON m.id_medico = ag.id_medico

        INNER JOIN unidade_saude u
            ON u.id_unidade = ag.id_unidade

        WHERE ag.id_medico = :id_medico
          AND ag.id_unidade = :id_unidade
          AND ag.data >= CURDATE()
          AND ag.status_horario = 'disponivel'

        ORDER BY ag.data ASC
    ");

    $stmt->execute([
        "id_medico" => $idMedico,
        "id_unidade" => $idUnidade
    ]);

    $datas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        "sucesso" => true,
        "tipo" => "datas",
        "datas" => $datas
    ]);

    exit;
}

/*
 * Valida a data.
 */
$dataObj = DateTime::createFromFormat("Y-m-d", $data);

if (
    !$dataObj ||
    $dataObj->format("Y-m-d") !== $data
) {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Data inválida."
    ]);

    exit;
}

/*
 * Retorna os horários.
 */
$stmt = $pdo->prepare("
    SELECT
        ag.id_agenda,
        ag.data,
        ag.horario_disponivel,
        ag.id_medico,
        ag.id_servico,
        ag.id_unidade,
        s.tipo_atendimento,
        s.duracao
    FROM agenda ag

    INNER JOIN servico_saude s
        ON s.id_servico = ag.id_servico

    WHERE ag.id_medico = :id_medico
      AND ag.id_unidade = :id_unidade
      AND ag.data = :data
      AND ag.status_horario = 'disponivel'

    ORDER BY
        ag.horario_disponivel ASC
");

$stmt->execute([
    "id_medico" => $idMedico,
    "id_unidade" => $idUnidade,
    "data" => $data
]);

$horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "sucesso" => true,
    "tipo" => "horarios",
    "horarios" => $horarios
]);