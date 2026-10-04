<?php

session_start();

if (
    !isset($_SESSION["id_administrador"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Administrador"
) {
    header(
        "Location: ../login.php?erro=" .
        urlencode("Você não possui permissão para acessar esta área.")
    );
    exit;
}

require_once __DIR__ . "/../conexao/conexao.php";

$tituloPagina = "AgendaSaúde | Consultas";

$stmt = $pdo->query("
    SELECT
        ag.id_agendamento,
        ag.data_agendamento,
        ag.horario,
        ag.status,
        u.nome AS paciente,
        u.cpf,
        m.nome AS medico,
        m.crm,
        m.uf_crm,
        e.nome_especialidade,
        s.tipo_atendimento,
        us.nome_unidade
    FROM agendamento ag
    INNER JOIN usuario u
        ON u.id_usuario = ag.id_usuario
    INNER JOIN agenda a
        ON a.id_agenda = ag.id_agenda
    INNER JOIN medico m
        ON m.id_medico = ag.id_medico
    INNER JOIN especialidade_medica e
        ON e.id_especialidade = m.id_especialidade
    INNER JOIN servico_saude s
        ON s.id_servico = ag.id_servico
    INNER JOIN unidade_saude us
        ON us.id_unidade = ag.id_unidade
    ORDER BY
        ag.data_agendamento DESC,
        ag.horario DESC
");

$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";
?>

<main class="container">

    <h1 class="page-title">Consultas</h1>

    <section class="table-card">

        <?php if (empty($consultas)): ?>

            <p>Nenhuma consulta encontrada.</p>

        <?php else: ?>

            <div style="overflow-x:auto;">

                <table>

                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Paciente</th>
                            <th>CPF</th>
                            <th>Médico</th>
                            <th>CRM</th>
                            <th>Especialidade</th>
                            <th>Atendimento</th>
                            <th>Unidade</th>
                            <th>Status</th>
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
                                            "d/m/Y",
                                            strtotime(
                                                $consulta["data_agendamento"]
                                            )
                                        )
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        date(
                                            "H:i",
                                            strtotime(
                                                $consulta["horario"]
                                            )
                                        )
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $consulta["paciente"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $consulta["cpf"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $consulta["medico"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $consulta["crm"] .
                                        "/" .
                                        $consulta["uf_crm"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $consulta["nome_especialidade"]
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

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </section>

</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>