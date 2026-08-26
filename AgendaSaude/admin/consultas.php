<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo_usuario"] !== "Administrador") {
    header("Location: ../login.php?erro=" . urlencode("Acesso não autorizado."));
    exit;
}

$tituloPagina = "AgendaSaúde | Consultas";
require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$resultado = mysqli_query(
    $conexao,
    "SELECT a.id_agendamento, a.status,
            p.nome AS paciente,
            m.nome AS medico,
            e.nome_especialidade,
            us.nome_unidade,
            h.data_consulta, h.horario
     FROM agendamentos a
     INNER JOIN usuarios p ON p.id_usuario = a.id_usuario
     INNER JOIN medicos md ON md.id_medico = a.id_medico
     INNER JOIN usuarios m ON m.id_usuario = md.id_usuario
     INNER JOIN especialidades e ON e.id_especialidade = md.id_especialidade
     INNER JOIN unidades_saude us ON us.id_unidade = md.id_unidade
     INNER JOIN horarios h ON h.id_horario = a.id_horario
     ORDER BY h.data_consulta DESC, h.horario DESC"
);
?>

<main class="container">
    <h1 class="page-title">Agendamentos</h1>

    <section class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Paciente</th><th>Médico</th><th>Especialidade</th>
                    <th>Unidade</th><th>Data</th><th>Hora</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($c = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?= htmlspecialchars($c["paciente"]) ?></td>
                    <td><?= htmlspecialchars($c["medico"]) ?></td>
                    <td><?= htmlspecialchars($c["nome_especialidade"]) ?></td>
                    <td><?= htmlspecialchars($c["nome_unidade"]) ?></td>
                    <td><?= date("d/m/Y", strtotime($c["data_consulta"])) ?></td>
                    <td><?= substr($c["horario"], 0, 5) ?></td>
                    <td><span class="badge"><?= htmlspecialchars($c["status"]) ?></span></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>