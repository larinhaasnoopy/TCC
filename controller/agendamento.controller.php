```php
<?php

session_start();

if (
    !isset($_SESSION["id_usuario"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Paciente"
) {
    header(
        "Location: ../login.php?erro=" .
        urlencode("Você precisa estar logado como paciente.")
    );
    exit;
}

require_once __DIR__ . "/../conexao/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../paciente/agendar.php");
    exit;
}

$idUsuario = (int) $_SESSION["id_usuario"];
$idAgenda = (int) ($_POST["id_agenda"] ?? 0);

if ($idAgenda <= 0) {
    header(
        "Location: ../paciente/agendar.php?erro=" .
        urlencode("Selecione um horário válido.")
    );
    exit;
}

try {

    $pdo->beginTransaction();

    /* Verifica o horário */

    $stmt = $pdo->prepare("
        SELECT
            ag.id_agenda,
            ag.data,
            ag.horario_disponivel,
            ag.status_horario,
            ag.id_medico,
            ag.id_servico,
            ag.id_unidade
        FROM agenda ag
        WHERE ag.id_agenda = :id_agenda
        FOR UPDATE
    ");

    $stmt->execute([
        "id_agenda" => $idAgenda
    ]);

    $agenda = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$agenda) {
        throw new Exception(
            "O horário selecionado não foi encontrado."
        );
    }

    if ($agenda["status_horario"] !== "disponivel") {
        throw new Exception(
            "Esse horário não está mais disponível."
        );
    }

    /* Verifica se o horário já passou */

    $dataHorario = $agenda["data"] . " " . $agenda["horario_disponivel"];

    if (strtotime($dataHorario) < time()) {
        throw new Exception(
            "Esse horário já passou."
        );
    }

    /* Verifica se já existe agendamento */

    $stmt = $pdo->prepare("
        SELECT id_agendamento
        FROM agendamento
        WHERE id_agenda = :id_agenda
        LIMIT 1
    ");

    $stmt->execute([
        "id_agenda" => $idAgenda
    ]);

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        throw new Exception(
            "Esse horário já foi agendado."
        );
    }

    /* Cria o agendamento */

    $stmt = $pdo->prepare("
        INSERT INTO agendamento (
            data_agendamento,
            horario,
            status,
            lembrete_ativo,
            id_usuario,
            id_agenda,
            id_medico,
            id_servico,
            id_unidade
        )
        VALUES (
            :data_agendamento,
            :horario,
            'pendente',
            0,
            :id_usuario,
            :id_agenda,
            :id_medico,
            :id_servico,
            :id_unidade
        )
    ");

    $stmt->execute([
        "data_agendamento" => $agenda["data"],
        "horario" => $agenda["horario_disponivel"],
        "id_usuario" => $idUsuario,
        "id_agenda" => $agenda["id_agenda"],
        "id_medico" => $agenda["id_medico"],
        "id_servico" => $agenda["id_servico"],
        "id_unidade" => $agenda["id_unidade"]
    ]);

    /* Reserva o horário */

    $stmt = $pdo->prepare("
        UPDATE agenda
        SET status_horario = 'reservado'
        WHERE id_agenda = :id_agenda
          AND status_horario = 'disponivel'
    ");

    $stmt->execute([
        "id_agenda" => $idAgenda
    ]);

    if ($stmt->rowCount() !== 1) {
        throw new Exception(
            "Não foi possível reservar esse horário."
        );
    }

    $pdo->commit();

    header(
        "Location: ../paciente/minhas_consultas.php?sucesso=" .
        urlencode("Consulta agendada com sucesso.")
    );
    exit;

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    header(
        "Location: ../paciente/agendar.php?erro=" .
        urlencode($e->getMessage())
    );
    exit;
}
```
