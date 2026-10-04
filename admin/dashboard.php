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

$tituloPagina = "AgendaSaúde | Administração";

function totalTabela(PDO $pdo, string $tabela): int
{
    $tabelasPermitidas = [
        "usuario",
        "medico",
        "unidade_saude",
        "agendamento",
        "especialidade_medica"
    ];

    if (!in_array($tabela, $tabelasPermitidas, true)) {
        return 0;
    }

    $stmt = $pdo->query(
        "SELECT COUNT(*) FROM {$tabela}"
    );

    return (int) $stmt->fetchColumn();
}

$totalUsuarios = totalTabela($pdo, "usuario");
$totalMedicos = totalTabela($pdo, "medico");
$totalUnidades = totalTabela($pdo, "unidade_saude");
$totalAgendamentos = totalTabela($pdo, "agendamento");
$totalEspecialidades = totalTabela($pdo, "especialidade_medica");

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";
?>

<main class="container">

    <div class="dashboard-header">
        <div>
            <h1 class="page-title">Painel Administrativo</h1>

            <p>
                Olá,
                <strong>
                    <?= htmlspecialchars(
                        $_SESSION["nome_usuario"] ?? "Administrador"
                    ) ?>
                </strong>
            </p>
        </div>
    </div>

    <section class="dashboard-cards">

        <div class="dashboard-card">
            <strong><?= $totalUsuarios ?></strong>
            <span>Pacientes</span>
        </div>

        <div class="dashboard-card">
            <strong><?= $totalMedicos ?></strong>
            <span>Médicos</span>
        </div>

        <div class="dashboard-card">
            <strong><?= $totalUnidades ?></strong>
            <span>Unidades</span>
        </div>

        <div class="dashboard-card">
            <strong><?= $totalAgendamentos ?></strong>
            <span>Agendamentos</span>
        </div>

        <div class="dashboard-card">
            <strong><?= $totalEspecialidades ?></strong>
            <span>Especialidades</span>
        </div>

    </section>

    <section class="grid">

        <div class="card">
            <div class="card-title">
                Gerenciar usuários
            </div>

            <p>
                Visualize os pacientes cadastrados no sistema.
            </p>

            <a
                href="usuarios.php"
                class="btn-primary"
            >
                Ver usuários
            </a>
        </div>

        <div class="card">
            <div class="card-title">
                Gerenciar médicos
            </div>

            <p>
                Consulte e cadastre médicos e suas especialidades.
            </p>

            <a
                href="medicos.php"
                class="btn-primary"
            >
                Ver médicos
            </a>
        </div>

        <div class="card">
            <div class="card-title">
                Gerenciar unidades
            </div>

            <p>
                Consulte as unidades de saúde cadastradas.
            </p>

            <a
                href="unidades.php"
                class="btn-primary"
            >
                Ver unidades
            </a>
        </div>

        <div class="card">
            <div class="card-title">
                Gerenciar especialidades
            </div>

            <p>
                Consulte as especialidades médicas disponíveis.
            </p>

            <a
                href="especialidades.php"
                class="btn-primary"
            >
                Ver especialidades
            </a>
        </div>

        <div class="card">
            <div class="card-title">
                Gerenciar horários
            </div>

            <p>
                Cadastre e consulte os horários disponíveis.
            </p>

            <a
                href="horarios.php"
                class="btn-primary"
            >
                Ver horários
            </a>
        </div>

        <div class="card">
            <div class="card-title">
                Consultas
            </div>

            <p>
                Consulte os agendamentos realizados pelos pacientes.
            </p>

            <a
                href="consultas.php"
                class="btn-primary"
            >
                Ver consultas
            </a>
        </div>

    </section>

</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>