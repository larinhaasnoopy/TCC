```php
<?php
session_start();

if (
    !isset($_SESSION["id_usuario"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Paciente"
) {
    header(
        "Location: ../login.php?erro=" .
        urlencode("Faça login para acessar seu painel.")
    );
    exit;
}

$tituloPagina = "AgendaSaúde | Dashboard";

require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$idUsuario = (int) $_SESSION["id_usuario"];
$nomeUsuario = $_SESSION["nome_usuario"] ?? "Paciente";

$stmt = $pdo->prepare("
    SELECT COUNT(*) AS total
    FROM agendamento
    WHERE id_usuario = :id_usuario
");
$stmt->execute([
    "id_usuario" => $idUsuario
]);

$totalConsultas = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT COUNT(*) AS total
    FROM agendamento
    WHERE id_usuario = :id_usuario
      AND status IN ('pendente', 'confirmado')
");
$stmt->execute([
    "id_usuario" => $idUsuario
]);

$consultasAtivas = (int) $stmt->fetchColumn();
?>

<main class="container pagina-interna">

    <section class="dashboard-header">
        <div>
            <span class="pagina-kicker">PAINEL DO PACIENTE</span>

            <h1 class="page-title">
                Olá, <?= htmlspecialchars($nomeUsuario, ENT_QUOTES, "UTF-8") ?>! 👋
            </h1>

            <p>
                Consulte seus agendamentos ou marque uma nova consulta.
            </p>
        </div>

        <a class="btn-primary" href="agendar.php">
            Nova consulta
        </a>
    </section>

    <section class="dashboard-cards">

        <div class="dashboard-card">
            <span>Consultas</span>
            <strong><?= $totalConsultas ?></strong>
        </div>

        <div class="dashboard-card">
            <span>Consultas ativas</span>
            <strong><?= $consultasAtivas ?></strong>
        </div>

        <div class="dashboard-card">
            <span>Perfil</span>
            <strong>100%</strong>
        </div>

        <div class="dashboard-card">
            <span>Suporte</span>
            <strong>24h</strong>
        </div>

    </section>

    <section class="grid dashboard-acoes">

        <div class="card">
            <h3>📅 Agendar consulta</h3>

            <p>
                Escolha a especialidade, o médico, a unidade, a data e o horário.
            </p>

            <a class="btn-primary" href="agendar.php">
                Agendar
            </a>
        </div>

        <div class="card">
            <h3>📋 Minhas consultas</h3>

            <p>
                Consulte seus agendamentos, horários, médicos e unidades.
            </p>

            <a class="btn-secondary" href="minhas_consultas.php">
                Ver consultas
            </a>
        </div>

        <div class="card">
            <h3>👤 Meu perfil</h3>

            <p>
                Confira seus dados cadastrados no AgendaSaúde.
            </p>

            <a class="btn-secondary" href="perfil.php">
                Ver perfil
            </a>
        </div>

    </section>

</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
```
