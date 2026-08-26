<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php?erro=" . urlencode("Faça login para continuar."));
    exit;
}

$tituloPagina = "AgendaSaúde | Minhas Consultas";
require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$idUsuario = (int)$_SESSION["id_usuario"];

$stmt = mysqli_prepare(
    $conexao,
    "SELECT a.id_agendamento, a.status, a.motivo,
            h.data_consulta, h.horario,
            u.nome AS medico,
            e.nome_especialidade,
            us.nome_unidade
     FROM agendamentos a
     INNER JOIN horarios h ON h.id_horario = a.id_horario
     INNER JOIN medicos m ON m.id_medico = a.id_medico
     INNER JOIN usuarios u ON u.id_usuario = m.id_usuario
     INNER JOIN especialidades e ON e.id_especialidade = m.id_especialidade
     INNER JOIN unidades_saude us ON us.id_unidade = m.id_unidade
     WHERE a.id_usuario = ?
     ORDER BY h.data_consulta DESC, h.horario DESC"
);

mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$consultas = mysqli_stmt_get_result($stmt);
?>

<main class="container">
    <h1 class="page-title">Minhas consultas</h1>

    <?php if (isset($_GET["sucesso"])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET["sucesso"]) ?></div>
    <?php endif; ?>

    <section class="table-card">
        <?php if (mysqli_num_rows($consultas) === 0): ?>
            <p>Você ainda não possui consultas agendadas.</p>
            <br>
            <a class="btn-primary" href="agendar.php">Agendar consulta</a>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Médico</th>
                        <th>Especialidade</th>
                        <th>Unidade</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($consulta = mysqli_fetch_assoc($consultas)): ?>
                    <tr>
                        <td><?= date("d/m/Y", strtotime($consulta["data_consulta"])) ?></td>
                        <td><?= substr($consulta["horario"], 0, 5) ?></td>
                        <td><?= htmlspecialchars($consulta["medico"]) ?></td>
                        <td><?= htmlspecialchars($consulta["nome_especialidade"]) ?></td>
                        <td><?= htmlspecialchars($consulta["nome_unidade"]) ?></td>
                        <td><span class="badge"><?= htmlspecialchars($consulta["status"]) ?></span></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>