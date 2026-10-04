<?php

session_start();

if (
    !isset($_SESSION["id_medico"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Medico"
) {
    header(
        "Location: ../login.php?erro=" .
        urlencode("Você não possui permissão para acessar esta área.")
    );
    exit;
}

require_once __DIR__ . "/../conexao/conexao.php";

$tituloPagina = "AgendaSaúde | Minha Agenda";

$idMedico = (int) $_SESSION["id_medico"];
$nomeMedico = $_SESSION["nome_usuario"] ?? "Médico";
$crmMedico = $_SESSION["crm_medico"] ?? "";
$ufCrm = $_SESSION["uf_crm_medico"] ?? "";

$dataSelecionada = $_GET["data"] ?? date("Y-m-d");

if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dataSelecionada)) {
    $dataSelecionada = date("Y-m-d");
}

$erro = $_GET["erro"] ?? "";
$sucesso = $_GET["sucesso"] ?? "";

/* Dados do médico */
$stmtMedico = $pdo->prepare("
    SELECT
        m.nome,
        m.crm,
        m.uf_crm,
        e.nome_especialidade
    FROM medico m
    INNER JOIN especialidade_medica e
        ON e.id_especialidade = m.id_especialidade
    WHERE m.id_medico = :id_medico
    LIMIT 1
");

$stmtMedico->execute([
    "id_medico" => $idMedico
]);

$dadosMedico = $stmtMedico->fetch(PDO::FETCH_ASSOC);

if ($dadosMedico) {
    $nomeMedico = $dadosMedico["nome"];
    $crmMedico = $dadosMedico["crm"];
    $ufCrm = $dadosMedico["uf_crm"];
}

$crmCompleto = trim($crmMedico . "/" . $ufCrm);

/* Consultas do dia */
$stmtConsultas = $pdo->prepare("
    SELECT
        agm.id_agendamento,
        agm.data_agendamento,
        agm.horario,
        agm.status,
        u.nome,
        u.cpf,
        u.telefone,
        s.tipo_atendimento,
        us.nome_unidade,
        us.endereco,
        a.id_agenda
    FROM agendamento agm
    INNER JOIN usuario u
        ON u.id_usuario = agm.id_usuario
    INNER JOIN agenda a
        ON a.id_agenda = agm.id_agenda
    INNER JOIN servico_saude s
        ON s.id_servico = agm.id_servico
    INNER JOIN unidade_saude us
        ON us.id_unidade = agm.id_unidade
    WHERE a.id_medico = :id_medico
      AND agm.data_agendamento = :data
    ORDER BY agm.horario ASC
");

$stmtConsultas->execute([
    "id_medico" => $idMedico,
    "data" => $dataSelecionada
]);

$consultas = $stmtConsultas->fetchAll(PDO::FETCH_ASSOC);

/* Resumo */
$stmtResumo = $pdo->prepare("
    SELECT
        COUNT(*) AS total,
        SUM(
            CASE
                WHEN agm.status = 'confirmado'
                THEN 1
                ELSE 0
            END
        ) AS confirmadas,
        SUM(
            CASE
                WHEN agm.status = 'em_atendimento'
                THEN 1
                ELSE 0
            END
        ) AS atendimento,
        SUM(
            CASE
                WHEN agm.status = 'concluido'
                THEN 1
                ELSE 0
            END
        ) AS concluidas
    FROM agendamento agm
    INNER JOIN agenda a
        ON a.id_agenda = agm.id_agenda
    WHERE a.id_medico = :id_medico
      AND agm.data_agendamento = :data
");

$stmtResumo->execute([
    "id_medico" => $idMedico,
    "data" => $dataSelecionada
]);

$resumo = $stmtResumo->fetch(PDO::FETCH_ASSOC);

$total = (int) ($resumo["total"] ?? 0);
$confirmadas = (int) ($resumo["confirmadas"] ?? 0);
$emAtendimento = (int) ($resumo["atendimento"] ?? 0);
$concluidas = (int) ($resumo["concluidas"] ?? 0);

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";
?>

<main class="container">

    <div class="dashboard-header">
        <div>
            <h1 class="page-title">Minha Agenda</h1>

            <p>
                Olá, <strong><?= htmlspecialchars($nomeMedico) ?></strong>
            </p>

            <p>
                <?= htmlspecialchars($crmCompleto) ?>
            </p>
        </div>

        <div>
            <form method="GET">
                <label for="data">Data</label>

                <input
                    type="date"
                    id="data"
                    name="data"
                    value="<?= htmlspecialchars($dataSelecionada) ?>"
                    onchange="this.form.submit()"
                >
            </form>
        </div>
    </div>

    <?php if ($erro !== ""): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <?php if ($sucesso !== ""): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($sucesso) ?>
        </div>
    <?php endif; ?>

    <section class="dashboard-cards">

        <div class="dashboard-card">
            <strong><?= $total ?></strong>
            <span>Consultas</span>
        </div>

        <div class="dashboard-card">
            <strong><?= $confirmadas ?></strong>
            <span>Confirmadas</span>
        </div>

        <div class="dashboard-card">
            <strong><?= $emAtendimento ?></strong>
            <span>Em atendimento</span>
        </div>

        <div class="dashboard-card">
            <strong><?= $concluidas ?></strong>
            <span>Concluídas</span>
        </div>

    </section>

    <section class="card">

        <div class="card-title">
            Agenda de
            <?= date("d/m/Y", strtotime($dataSelecionada)) ?>
        </div>

        <?php if (empty($consultas)): ?>

            <p>
                Não há consultas agendadas para esta data.
            </p>

        <?php else: ?>

            <div class="table-card">

                <table>

                    <thead>
                        <tr>
                            <th>Horário</th>
                            <th>Paciente</th>
                            <th>CPF</th>
                            <th>Telefone</th>
                            <th>Atendimento</th>
                            <th>Unidade</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($consultas as $consulta): ?>

                            <?php
                            $status = $consulta["status"];

                            $statusTexto = match ($status) {
                                "pendente" => "Pendente",
                                "confirmado" => "Confirmado",
                                "cancelado" => "Cancelado",
                                "em_atendimento" => "Em atendimento",
                                "concluido" => "Concluído",
                                default => ucfirst($status)
                            };
                            ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars(
                                        date(
                                            "H:i",
                                            strtotime($consulta["horario"])
                                        )
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $consulta["nome"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $consulta["cpf"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $consulta["telefone"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $consulta["tipo_atendimento"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $consulta["nome_unidade"]
                                    ) ?>
                                </td>

                                <td>
                                    <span class="badge badge-<?= htmlspecialchars($status) ?>">
                                        <?= htmlspecialchars($statusTexto) ?>
                                    </span>
                                </td>

                                <td>

                                    <?php if ($status === "confirmado"): ?>

                                        <form
                                            method="POST"
                                            action="../controller/atendimento.controller.php"
                                        >
                                            <input
                                                type="hidden"
                                                name="id_agendamento"
                                                value="<?= (int) $consulta["id_agendamento"] ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="data_filtro"
                                                value="<?= htmlspecialchars($dataSelecionada) ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="acao"
                                                value="iniciar"
                                            >

                                            <button
                                                type="submit"
                                                class="btn-primary"
                                            >
                                                Iniciar
                                            </button>
                                        </form>

                                    <?php elseif ($status === "em_atendimento"): ?>

                                        <form
                                            method="POST"
                                            action="../controller/atendimento.controller.php"
                                        >
                                            <input
                                                type="hidden"
                                                name="id_agendamento"
                                                value="<?= (int) $consulta["id_agendamento"] ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="data_filtro"
                                                value="<?= htmlspecialchars($dataSelecionada) ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="acao"
                                                value="concluir"
                                            >

                                            <button
                                                type="submit"
                                                class="btn-primary"
                                            >
                                                Concluir
                                            </button>
                                        </form>

                                    <?php else: ?>

                                        —

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </section>

</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>