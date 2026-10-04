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

$idEspecialidade = (int) ($_GET["id_especialidade"] ?? 0);

if ($idEspecialidade <= 0) {

    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Especialidade inválida."
    ]);

    exit;
}

$stmt = $pdo->prepare("
    SELECT
        m.id_medico,
        m.nome,
        m.crm,
        m.uf_crm,
        m.id_especialidade,
        e.nome_especialidade

    FROM medico m

    INNER JOIN especialidade_medica e
        ON e.id_especialidade = m.id_especialidade

    WHERE m.id_especialidade = :id_especialidade

    ORDER BY m.nome ASC
");

$stmt->execute([
    "id_especialidade" => $idEspecialidade
]);

$medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "sucesso" => true,
    "medicos" => $medicos
]);