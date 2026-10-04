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

$tituloPagina = "AgendaSaúde | Horários";

$erro = $_GET["erro"] ?? "";
$sucesso = $_GET["sucesso"] ?? "";

/* Cadastro de horário */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = $_POST["data"] ?? "";
    $horario = $_POST["horario"] ?? "";
    $idMedico = (int) ($_POST["id_medico"] ?? 0);
    $idServico = (int) ($_POST["id_servico"] ?? 0);
    $idUnidade = (int) ($_POST["id_unidade"] ?? 0);

    if (
        $data === "" ||
        $horario === "" ||
        $idMedico <= 0 ||
        $idServico <= 0 ||
        $idUnidade <= 0
    ) {
        header(
            "Location: horarios.php?erro=" .
            urlencode("Preencha todos os campos.")
        );
        exit;
    }

    if ($data < date("Y-m-d")) {
        header(
            "Location: horarios.php?erro=" .
            urlencode("A data não pode ser anterior a hoje.")
        );
        exit;
    }

    /* Verifica médico */
    $stmt = $pdo->prepare("
        SELECT id_medico, id_especialidade
        FROM medico
        WHERE id_medico = :id
        LIMIT 1
    ");

    $stmt->execute([
        "id" => $idMedico
    ]);

    $medico = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$medico) {
        header(
            "Location: horarios.php?erro=" .
            urlencode("Médico não encontrado.")
        );
        exit;
    }

    /* Verifica serviço */
    $stmt = $pdo->prepare("
        SELECT id_servico
        FROM servico_saude
        WHERE id_servico = :id
          AND id_especialidade = :especialidade
        LIMIT 1
    ");

    $stmt->execute([
        "id" => $idServico,
        "especialidade" => $medico["id_especialidade"]
    ]);

    if (!$stmt->fetch()) {
        header(
            "Location: horarios.php?erro=" .
            urlencode("O serviço não pertence à especialidade do médico.")
        );
        exit;
    }

    /* Verifica unidade */
    $stmt = $pdo->prepare("
        SELECT id_unidade
        FROM unidade_saude
        WHERE id_unidade = :id
        LIMIT 1
    ");

    $stmt->execute([
        "id" => $idUnidade
    ]);

    if (!$stmt->fetch()) {
        header(
            "Location: horarios.php?erro=" .
            urlencode("Unidade não encontrada.")
        );
        exit;
    }

    /* Verifica horário duplicado */
    $stmt = $pdo->prepare("
        SELECT id_agenda
        FROM agenda
        WHERE id_medico = :id_medico
          AND data = :data
          AND horario_disponivel = :horario
        LIMIT 1
    ");

    $stmt->execute([
        "id_medico" => $idMedico,
        "data" => $data,
        "horario" => $horario
    ]);

    if ($stmt->fetch()) {
        header(
            "Location: horarios.php?erro=" .
            urlencode("Esse médico já possui um horário nessa data.")
        );
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO agenda (
            data,
            horario_disponivel,
            status_horario,
            id_medico,
            id_servico,
            id_unidade
        )
        VALUES (
            :data,
            :horario,
            'disponivel',
            :id_medico,
            :id_servico,
            :id_unidade
        )
    ");

    $stmt->execute([
        "data" => $data,
        "horario" => $horario,
        "id_medico" => $idMedico,
        "id_servico" => $idServico,
        "id_unidade" => $idUnidade
    ]);

    header(
        "Location: horarios.php?sucesso=" .
        urlencode("Horário cadastrado com sucesso.")
    );
    exit;
}

/* Médicos */
$stmt = $pdo->query("
    SELECT
        m.id_medico,
        m.nome,
        m.crm,
        m.uf_crm,
        e.id_especialidade,
        e.nome_especialidade
    FROM medico m
    INNER JOIN especialidade_medica e
        ON e.id_especialidade = m.id_especialidade
    ORDER BY m.nome ASC
");

$medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Serviços */
$stmt = $pdo->query("
    SELECT
        s.id_servico,
        s.tipo_atendimento,
        s.id_especialidade,
        e.nome_especialidade
    FROM servico_saude s
    INNER JOIN especialidade_medica e
        ON e.id_especialidade = s.id_especialidade
    ORDER BY
        e.nome_especialidade ASC,
        s.tipo_atendimento ASC
");

$servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Unidades */
$stmt = $pdo->query("
    SELECT
        id_unidade,
        nome_unidade,
        localizacao
    FROM unidade_saude
    ORDER BY nome_unidade ASC
");

$unidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Horários */
$stmt = $pdo->query("
    SELECT
        a.id_agenda,
        a.data,
        a.horario_disponivel,
        a.status_horario,
        m.nome AS medico,
        m.crm,
        m.uf_crm,
        e.nome_especialidade,
        s.tipo_atendimento,
        us.nome_unidade
    FROM agenda a
    INNER JOIN medico m
        ON m.id_medico = a.id_medico
    INNER JOIN especialidade_medica e
        ON e.id_especialidade = m.id_especialidade
    INNER JOIN servico_saude s
        ON s.id_servico = a.id_servico
    INNER JOIN unidade_saude us
        ON us.id_unidade = a.id_unidade
    ORDER BY
        a.data ASC,
        a.horario_disponivel ASC
    LIMIT 200
");

$horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";
?>

<main class="container">

    <h1 class="page-title">Gerenciar Horários</h1>

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

    <section class="form-card">

        <h2 class="card-title">
            Cadastrar horário
        </h2>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <label for="medico">
                        Médico
                    </label>

                    <select
                        name="id_medico"
                        id="medico"
                        required
                    >
                        <option value="">
                            Selecione o médico
                        </option>

                        <?php foreach ($medicos as $medico): ?>

                            <option
                                value="<?= (int) $medico["id_medico"] ?>"
                                data-especialidade="<?= (int) $medico["id_especialidade"] ?>"
                            >
                                <?= htmlspecialchars(
                                    $medico["nome"] .
                                    " - CRM " .
                                    $medico["crm"] .
                                    "/" .
                                    $medico["uf_crm"] .
                                    " - " .
                                    $medico["nome_especialidade"]
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label for="servico">
                        Serviço
                    </label>

                    <select
                        name="id_servico"
                        id="servico"
                        required
                    >
                        <option value="">
                            Selecione o serviço
                        </option>

                        <?php foreach ($servicos as $servico): ?>

                            <option
                                value="<?= (int) $servico["id_servico"] ?>"
                                data-especialidade="<?= (int) $servico["id_especialidade"] ?>"
                            >
                                <?= htmlspecialchars(
                                    $servico["nome_especialidade"] .
                                    " - " .
                                    $servico["tipo_atendimento"]
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label for="unidade">
                        Unidade
                    </label>

                    <select
                        name="id_unidade"
                        id="unidade"
                        required
                    >
                        <option value="">
                            Selecione a unidade
                        </option>

                        <?php foreach ($unidades as $unidade): ?>

                            <option
                                value="<?= (int) $unidade["id_unidade"] ?>"
                            >
                                <?= htmlspecialchars(
                                    $unidade["nome_unidade"] .
                                    " - " .
                                    $unidade["localizacao"]
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label for="data">
                        Data
                    </label>

                    <input
                        type="date"
                        name="data"
                        id="data"
                        min="<?= date("Y-m-d") ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="horario">
                        Horário
                    </label>

                    <input
                        type="time"
                        name="horario"
                        id="horario"
                        required
                    >

                </div>

            </div>

            <div class="form-actions">

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Cadastrar horário
                </button>

            </div>

        </form>

    </section>

    <section class="table-card">

        <h2 class="card-title">
            Horários cadastrados
        </h2>

        <?php if (empty($horarios)): ?>

            <p>Nenhum horário cadastrado.</p>

        <?php else: ?>

            <div style="overflow-x:auto;">

                <table>

                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Médico</th>
                            <th>Especialidade</th>
                            <th>Serviço</th>
                            <th>Unidade</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($horarios as $horario): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars(
                                        date(
                                            "d/m/Y",
                                            strtotime($horario["data"])
                                        )
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        date(
                                            "H:i",
                                            strtotime(
                                                $horario["horario_disponivel"]
                                            )
                                        )
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $horario["medico"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $horario["nome_especialidade"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $horario["tipo_atendimento"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $horario["nome_unidade"]
                                    ) ?>
                                </td>

                                <td>
                                    <span class="badge badge-<?= htmlspecialchars($horario["status_horario"]) ?>">
                                        <?= htmlspecialchars(
                                            ucfirst(
                                                $horario["status_horario"]
                                            )
                                        ) ?>
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

<script>
const medicoSelect = document.getElementById("medico");
const servicoSelect = document.getElementById("servico");

function atualizarServicos() {

    const especialidade =
        medicoSelect.options[
            medicoSelect.selectedIndex
        ]?.dataset.especialidade || "";

    Array.from(servicoSelect.options).forEach(
        (option, index) => {

            if (index === 0) {
                option.hidden = false;
                return;
            }

            option.hidden =
                option.dataset.especialidade !== especialidade;
        }
    );

    servicoSelect.value = "";
}

medicoSelect.addEventListener(
    "change",
    atualizarServicos
);
</script>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>