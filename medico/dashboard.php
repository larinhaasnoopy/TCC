<?php
session_start();

if (
    !isset($_SESSION["id_medico"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Medico"
) {
    header("Location: ../login.php?erro=" . urlencode("Acesso não autorizado."));
    exit;
}

$tituloPagina = "AgendaSaúde | Minha Agenda";
require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$idMedico = (int)($_SESSION["id_medico"] ?? 0);

/* Dados do médico (especialidade não fica na sessão) */
$stmt = $pdo->prepare(
    "SELECT m.nome, m.crm, e.nome_especialidade
     FROM medico m
     INNER JOIN especialidade_medica e ON e.id_especialidade = m.id_especialidade
     WHERE m.id_medico = :id"
);
$stmt->execute(["id" => $idMedico]);
$medico = $stmt->fetch();

/* Data selecionada (padrão: hoje) */
$dataFiltro = $_GET["data"] ?? date("Y-m-d");
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $dataFiltro)) {
    $dataFiltro = date("Y-m-d");
}

$diaAnterior = date("Y-m-d", strtotime($dataFiltro . " -1 day"));
$diaSeguinte = date("Y-m-d", strtotime($dataFiltro . " +1 day"));

/* Cards de resumo do dia */
$stmt = $pdo->prepare(
    "SELECT
        COUNT(*) AS total,
        SUM(CASE WHEN ag.status IN ('pendente','confirmado') THEN 1 ELSE 0 END) AS aguardando,
        SUM(CASE WHEN ag.status = 'concluido' THEN 1 ELSE 0 END) AS concluidas
     FROM agendamento ag
     INNER JOIN agenda a ON a.id_agenda = ag.id_agenda
     WHERE a.id_medico = :id_medico
       AND ag.data_agendamento = :data
       AND ag.status <> 'cancelado'"
);
$stmt->execute(["id_medico" => $idMedico, "data" => $dataFiltro]);
$resumo = $stmt->fetch();

/* Agenda do dia */
$stmt = $pdo->prepare(
    "SELECT ag.id_agendamento, ag.horario, ag.status,
            u.nome AS paciente, s.tipo_atendimento, us.nome_unidade
     FROM agendamento ag
     INNER JOIN agenda a ON a.id_agenda = ag.id_agenda
     INNER JOIN usuario u ON u.id_usuario = ag.id_usuario
     INNER JOIN servico_saude s ON s.id_servico = a.id_servico
     INNER JOIN unidade_saude us ON us.id_unidade = a.id_unidade
     WHERE a.id_medico = :id_medico
       AND ag.data_agendamento = :data
       AND ag.status <> 'cancelado'
     ORDER BY ag.horario"
);
$stmt->execute(["id_medico" => $idMedico, "data" => $dataFiltro]);
$agenda = $stmt->fetchAll();

$rotulosStatus = [
    "pendente" => "Pendente",
    "confirmado" => "Confirmado",
    "em_atendimento" => "Em atendimento",
    "concluido" => "Concluído",
];
?>

<main class="container">

    <div class="dashboard-header">
        <div>
            <h1 class="page-title">
                Olá, <?= htmlspecialchars($medico["nome"] ?? $_SESSION["nome_usuario"]) ?>! 🩺
            </h1>
            <p>
                <?= htmlspecialchars($medico["crm"] ?? "") ?>
                <?= !empty($medico["nome_especialidade"]) ? " — " . htmlspecialchars($medico["nome_especialidade"]) : "" ?>
            </p>
        </div>

        <form method="GET" style="display:flex;align-items:center;gap:10px;">
            <a class="btn-secondary" href="?data=<?= $diaAnterior ?>">← Dia anterior</a>
            <input type="date" name="data" value="<?= htmlspecialchars($dataFiltro) ?>" onchange="this.form.submit()">
            <a class="btn-secondary" href="?data=<?= $diaSeguinte ?>">Dia seguinte →</a>
        </form>
    </div>

    <?php if (isset($_GET["sucesso"])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET["sucesso"]) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET["erro"])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_GET["erro"]) ?></div>
    <?php endif; ?>

    <div class="dashboard-cards">
        <div class="dashboard-card">
            Consultas do dia
            <strong><?= (int)($resumo["total"] ?? 0) ?></strong>
        </div>
        <div class="dashboard-card">
            Pacientes aguardando
            <strong><?= (int)($resumo["aguardando"] ?? 0) ?></strong>
        </div>
        <div class="dashboard-card">
            Concluídas
            <strong><?= (int)($resumo["concluidas"] ?? 0) ?></strong>
        </div>
    </div>

    <section class="table-card">
        <?php if (count($agenda) === 0): ?>
            <p style="padding:20px;text-align:center;color:#64748b;">
                Nenhum atendimento agendado para <?= date("d/m/Y", strtotime($dataFiltro)) ?>.
            </p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Horário</th>
                        <th>Paciente</th>
                        <th>Serviço</th>
                        <th>Unidade</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($agenda as $item): ?>
                    <tr>
                        <td><?= substr($item["horario"], 0, 5) ?></td>
                        <td><?= htmlspecialchars($item["paciente"]) ?></td>
                        <td><?= htmlspecialchars($item["tipo_atendimento"]) ?></td>
                        <td><?= htmlspecialchars($item["nome_unidade"]) ?></td>
                        <td>
                            <span class="badge badge-<?= htmlspecialchars($item["status"]) ?>">
                                <?= $rotulosStatus[$item["status"]] ?? htmlspecialchars($item["status"]) ?>
                            </span>
                        </td>
                        <td>
                            <?php if (in_array($item["status"], ["pendente", "confirmado"], true)): ?>
                                <form action="../controller/atendimento.controller.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_agendamento" value="<?= $item["id_agendamento"] ?>">
                                    <input type="hidden" name="acao" value="iniciar">
                                    <input type="hidden" name="data_filtro" value="<?= htmlspecialchars($dataFiltro) ?>">
                                    <button type="submit" class="btn-secondary" style="padding:8px 14px;min-height:auto;">Iniciar Atendimento</button>
                                </form>
                            <?php elseif ($item["status"] === "em_atendimento"): ?>
                                <form action="../controller/atendimento.controller.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_agendamento" value="<?= $item["id_agendamento"] ?>">
                                    <input type="hidden" name="acao" value="concluir">
                                    <input type="hidden" name="data_filtro" value="<?= htmlspecialchars($dataFiltro) ?>">
                                    <button type="submit" class="btn-primary" style="padding:8px 14px;min-height:auto;">Concluir</button>
                                </form>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>

</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
