<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php?erro=" . urlencode("Faça login para acessar seu painel."));
    exit;
}

$tituloPagina = "AgendaSaúde | Dashboard";
require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$idUsuario = (int)$_SESSION["id_usuario"];

$stmt = mysqli_prepare(
    $conexao,
    "SELECT COUNT(*) total FROM agendamentos WHERE id_usuario = ?"
);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$totalConsultas = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))["total"] ?? 0;

$stmt = mysqli_prepare(
    $conexao,
    "SELECT COUNT(*) total FROM agendamentos
     WHERE id_usuario = ? AND status IN ('Agendada','Confirmada')"
);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$ativas = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))["total"] ?? 0;
?>

<main class="container">
    <div class="dashboard-header">
        <div>
            <h1 class="page-title">Olá, <?= htmlspecialchars($_SESSION["nome_usuario"]) ?>! 👋</h1>
            <p>Como podemos cuidar de você hoje?</p>
        </div>
        <a class="btn-primary" href="agendar.php">Nova consulta</a>
    </div>

    <div class="dashboard-cards">
        <div class="dashboard-card">
            Consultas
            <strong><?= (int)$totalConsultas ?></strong>
        </div>
        <div class="dashboard-card">
            Consultas ativas
            <strong><?= (int)$ativas ?></strong>
        </div>
        <div class="dashboard-card">
            Perfil
            <strong>100%</strong>
        </div>
        <div class="dashboard-card">
            Suporte
            <strong>24h</strong>
        </div>
    </div>

    <div class="grid" style="grid-template-columns:repeat(3,1fr);">
        <div class="card">
            <h3>📅 Agendar consulta</h3>
            <p>Escolha especialidade, médico, data e horário.</p>
            <br>
            <a class="btn-primary" href="agendar.php">Agendar</a>
        </div>

        <div class="card">
            <h3>📋 Minhas consultas</h3>
            <p>Consulte seus agendamentos e horários.</p>
            <br>
            <a class="btn-secondary" href="minhas_consultas.php">Ver consultas</a>
        </div>

        <div class="card">
            <h3>👤 Meu perfil</h3>
            <p>Confira seus dados cadastrados.</p>
            <br>
            <a class="btn-secondary" href="perfil.php">Ver perfil</a>
        </div>
    </div>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>