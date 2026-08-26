<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo_usuario"] !== "Administrador") {
    header("Location: ../login.php?erro=" . urlencode("Acesso não autorizado."));
    exit;
}

$tituloPagina = "AgendaSaúde | Horários";
require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$medicos = mysqli_query(
    $conexao,
    "SELECT m.id_medico, u.nome, e.nome_especialidade
     FROM medicos m
     INNER JOIN usuarios u ON u.id_usuario = m.id_usuario
     INNER JOIN especialidades e ON e.id_especialidade = m.id_especialidade
     ORDER BY u.nome"
);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idMedico = (int)($_POST["id_medico"] ?? 0);
    $data = $_POST["data_consulta"] ?? "";
    $hora = $_POST["horario"] ?? "";

    if ($idMedico > 0 && $data !== "" && $hora !== "") {
        $stmt = mysqli_prepare(
            $conexao,
            "INSERT INTO horarios (id_medico, data_consulta, horario, disponivel)
             VALUES (?, ?, ?, 1)"
        );
        mysqli_stmt_bind_param($stmt, "iss", $idMedico, $data, $hora);
        mysqli_stmt_execute($stmt);
        header("Location: horarios.php?sucesso=" . urlencode("Horário cadastrado."));
        exit;
    }

    header("Location: horarios.php?erro=" . urlencode("Preencha todos os campos."));
    exit;
}

$horarios = mysqli_query(
    $conexao,
    "SELECT h.*, u.nome, e.nome_especialidade
     FROM horarios h
     INNER JOIN medicos m ON m.id_medico = h.id_medico
     INNER JOIN usuarios u ON u.id_usuario = m.id_usuario
     INNER JOIN especialidades e ON e.id_especialidade = m.id_especialidade
     ORDER BY h.data_consulta, h.horario"
);
?>

<main class="container">
    <h1 class="page-title">Horários dos médicos</h1>

    <?php if (isset($_GET["erro"])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_GET["erro"]) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET["sucesso"])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET["sucesso"]) ?></div>
    <?php endif; ?>

    <section class="form-card" style="margin-bottom:25px;">
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Médico</label>
                    <select name="id_medico" required>
                        <option value="">Selecione</option>
                        <?php while ($m = mysqli_fetch_assoc($medicos)): ?>
                            <option value="<?= $m["id_medico"] ?>">
                                <?= htmlspecialchars($m["nome"]) ?> — <?= htmlspecialchars($m["nome_especialidade"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Data</label>
                    <input type="date" name="data_consulta" required>
                </div>

                <div class="form-group">
                    <label>Horário</label>
                    <input type="time" name="horario" required>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn-primary">Adicionar horário</button>
            </div>
        </form>
    </section>

    <section class="table-card">
        <table>
            <thead><tr><th>Data</th><th>Hora</th><th>Médico</th><th>Especialidade</th><th>Disponível</th></tr></thead>
            <tbody>
            <?php while ($h = mysqli_fetch_assoc($horarios)): ?>
                <tr>
                    <td><?= date("d/m/Y", strtotime($h["data_consulta"])) ?></td>
                    <td><?= substr($h["horario"], 0, 5) ?></td>
                    <td><?= htmlspecialchars($h["nome"]) ?></td>
                    <td><?= htmlspecialchars($h["nome_especialidade"]) ?></td>
                    <td><?= $h["disponivel"] ? "Sim" : "Não" ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>