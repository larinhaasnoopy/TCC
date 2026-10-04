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

$tituloPagina = "AgendaSaúde | Médicos";

$erro = $_GET["erro"] ?? "";
$sucesso = $_GET["sucesso"] ?? "";

/* Especialidades */
$stmt = $pdo->query("
    SELECT
        id_especialidade,
        nome_especialidade
    FROM especialidade_medica
    ORDER BY nome_especialidade ASC
");

$especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Médicos */
$stmt = $pdo->query("
    SELECT
        m.id_medico,
        m.nome,
        m.crm,
        m.uf_crm,
        m.email,
        e.nome_especialidade
    FROM medico m
    INNER JOIN especialidade_medica e
        ON e.id_especialidade = m.id_especialidade
    ORDER BY m.nome ASC
");

$medicos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";
?>

<main class="container">

    <h1 class="page-title">Médicos</h1>

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
            Cadastrar médico
        </h2>

        <form
            method="POST"
            action="../controller/medico.controller.php"
        >

            <div class="form-grid">

                <div class="form-group">

                    <label for="nome">
                        Nome
                    </label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        maxlength="100"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="crm">
                        CRM
                    </label>

                    <input
                        type="text"
                        id="crm"
                        name="crm"
                        maxlength="5"
                        minlength="5"
                        pattern="[0-9]{5}"
                        inputmode="numeric"
                        placeholder="Ex.: 10001"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="uf_crm">
                        UF do CRM
                    </label>

                    <select
                        id="uf_crm"
                        name="uf_crm"
                        required
                    >
                        <option value="">
                            Selecione
                        </option>

                        <?php
                        $ufs = [
                            "AC", "AL", "AP", "AM", "BA", "CE",
                            "DF", "ES", "GO", "MA", "MT", "MS",
                            "MG", "PA", "PB", "PR", "PE", "PI",
                            "RJ", "RN", "RS", "RO", "RR", "SC",
                            "SP", "SE", "TO"
                        ];
                        ?>

                        <?php foreach ($ufs as $uf): ?>

                            <option value="<?= $uf ?>">
                                <?= $uf ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label for="email">
                        E-mail
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        maxlength="150"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="senha">
                        Senha
                    </label>

                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        required
                    >

                </div>

                <div class="form-group">

                    <label for="id_especialidade">
                        Especialidade
                    </label>

                    <select
                        id="id_especialidade"
                        name="id_especialidade"
                        required
                    >

                        <option value="">
                            Selecione a especialidade
                        </option>

                        <?php foreach ($especialidades as $especialidade): ?>

                            <option
                                value="<?= (int) $especialidade["id_especialidade"] ?>"
                            >
                                <?= htmlspecialchars(
                                    $especialidade["nome_especialidade"]
                                ) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>

            <div class="form-actions">

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Cadastrar médico
                </button>

            </div>

        </form>

    </section>

    <section class="table-card">

        <h2 class="card-title">
            Médicos cadastrados
        </h2>

        <?php if (empty($medicos)): ?>

            <p>Nenhum médico cadastrado.</p>

        <?php else: ?>

            <div style="overflow-x:auto;">

                <table>

                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CRM</th>
                            <th>E-mail</th>
                            <th>Especialidade</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($medicos as $medico): ?>

                            <tr>

                                <td>
                                    <?= htmlspecialchars(
                                        $medico["nome"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $medico["crm"] .
                                        "/" .
                                        $medico["uf_crm"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $medico["email"]
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $medico["nome_especialidade"]
                                    ) ?>
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
const crm = document.getElementById("crm");

crm.addEventListener("input", function () {
    this.value = this.value.replace(/\D/g, "").slice(0, 5);
});
</script>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>