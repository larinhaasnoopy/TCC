<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo_usuario"] !== "Administrador") {
    header("Location: ../login.php?erro=" . urlencode("Acesso restrito ao administrador."));
    exit;
}

$tituloPagina = "HealthCare+ | Administração";
require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

function totalTabela($conexao, $tabela) {
    $permitidas = ["usuarios", "medicos", "unidades_saude", "agendamentos", "especialidades"];
    if (!in_array($tabela, $permitidas, true)) return 0;

    $resultado = mysqli_query($conexao, "SELECT COUNT(*) total FROM $tabela");
    $linha = mysqli_fetch_assoc($resultado);
    return (int)$linha["total"];
}
?>

<main class="container">
    <div class="dashboard-header">
        <div>
            <h1 class="page-title">Painel Administrativo</h1>
            <p>Gerencie os dados do HealthCare+.</p>
        </div>
    </div>

    <div class="dashboard-cards">
        <div class="dashboard-card">Usuários<strong><?= totalTabela($conexao, "usuarios") ?></strong></div>
        <div class="dashboard-card">Médicos<strong><?= totalTabela($conexao, "medicos") ?></strong></div>
        <div class="dashboard-card">Unidades<strong><?= totalTabela($conexao, "unidades_saude") ?></strong></div>
        <div class="dashboard-card">Agendamentos<strong><?= totalTabela($conexao, "agendamentos") ?></strong></div>
    </div>

    <div class="cards">
        <div class="card"><h3>👥 Usuários</h3><p>Visualize os usuários cadastrados.</p><br><a class="btn-primary" href="usuarios.php">Gerenciar</a></div>
        <div class="card"><h3>🩺 Médicos</h3><p>Cadastre e consulte profissionais.</p><br><a class="btn-primary" href="medicos.php">Gerenciar</a></div>
        <div class="card"><h3>🏥 Unidades</h3><p>Cadastre unidades de saúde.</p><br><a class="btn-primary" href="unidades.php">Gerenciar</a></div>
        <div class="card"><h3>📚 Especialidades</h3><p>Consulte especialidades disponíveis.</p><br><a class="btn-primary" href="especialidades.php">Gerenciar</a></div>
        <div class="card"><h3>🕐 Horários</h3><p>Cadastre horários dos médicos.</p><br><a class="btn-primary" href="horarios.php">Gerenciar</a></div>
        <div class="card"><h3>📅 Consultas</h3><p>Veja os agendamentos realizados.</p><br><a class="btn-primary" href="consultas.php">Gerenciar</a></div>
    </div>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
