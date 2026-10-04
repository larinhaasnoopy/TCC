<?php
session_start();

$tituloPagina = "AgendaSaúde | Cadastro";

require_once __DIR__ . "/conexao/conexao.php";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/menu.php";

$tipo = strtolower(trim($_GET["tipo"] ?? $_POST["tipo"] ?? ""));

$tiposValidos = [
    "paciente",
    "medico",
    "administrador"
];

if ($tipo !== "" && !in_array($tipo, $tiposValidos, true)) {
    $tipo = "";
}

$erro = $_GET["erro"] ?? "";
$sucesso = $_GET["sucesso"] ?? "";

$old = $_SESSION["old_cadastro"] ?? [];
unset($_SESSION["old_cadastro"]);

$especialidades = [];

if ($tipo === "medico") {
    $stmt = $pdo->query("
        SELECT
            id_especialidade,
            nome_especialidade
        FROM especialidade_medica
        ORDER BY nome_especialidade ASC
    ");

    $especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function valorAntigo(string $campo, array $old): string
{
    return htmlspecialchars($old[$campo] ?? "", ENT_QUOTES, "UTF-8");
}
?>

<main class="auth-page">

    <section class="auth-card <?= $tipo === "medico" ? "auth-card--wide" : "" ?>">

        <div class="auth-header">

            <span class="auth-kicker">AgendaSaúde</span>

            <?php if ($tipo === ""): ?>

                <h1>Crie sua conta</h1>
                <p>Escolha o tipo de cadastro para continuar.</p>

            <?php elseif ($tipo === "paciente"): ?>

                <h1>Cadastro de paciente</h1>
                <p>Preencha seus dados para criar sua conta.</p>

            <?php elseif ($tipo === "medico"): ?>

                <h1>Cadastro de médico</h1>
                <p>Informe seus dados profissionais.</p>

            <?php else: ?>

                <h1>Cadastro de administrador</h1>
                <p>Preencha os dados para criar a conta administrativa.</p>

            <?php endif; ?>

        </div>

        <?php if ($erro !== ""): ?>

            <div class="mensagem mensagem-erro">
                <?= htmlspecialchars($erro) ?>
            </div>

        <?php endif; ?>

        <?php if ($sucesso !== ""): ?>

            <div class="mensagem mensagem-sucesso">
                <?= htmlspecialchars($sucesso) ?>
            </div>

        <?php endif; ?>

        <?php if ($tipo === ""): ?>

            <div class="tipo-opcoes">

                <a href="cadastro.php?tipo=paciente" class="tipo-opcao">
                    <span class="tipo-icone">👤</span>

                    <span class="tipo-texto">
                        <strong>Paciente</strong>
                        <small>Agende e acompanhe suas consultas.</small>
                    </span>

                    <span class="tipo-seta">→</span>
                </a>

                <a href="cadastro.php?tipo=medico" class="tipo-opcao">
                    <span class="tipo-icone">🩺</span>

                    <span class="tipo-texto">
                        <strong>Médico</strong>
                        <small>Gerencie sua agenda de atendimentos.</small>
                    </span>

                    <span class="tipo-seta">→</span>
                </a>

                <a href="cadastro.php?tipo=administrador" class="tipo-opcao">
                    <span class="tipo-icone">⚙️</span>

                    <span class="tipo-texto">
                        <strong>Administrador</strong>
                        <small>Gerencie usuários e serviços.</small>
                    </span>

                    <span class="tipo-seta">→</span>
                </a>

            </div>

        <?php else: ?>

            <form
                action="controller/cadastro.controller.php"
                method="POST"
                class="auth-form"
            >

                <input
                    type="hidden"
                    name="tipo"
                    value="<?= htmlspecialchars($tipo) ?>"
                >

                <div class="campo">

                    <label for="nome">Nome completo</label>

                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        maxlength="100"
                        value="<?= valorAntigo("nome", $old) ?>"
                        autocomplete="name"
                        required
                    >

                </div>

                <?php if ($tipo === "paciente"): ?>

                    <div class="campo">

                        <label for="cpf">CPF</label>

                        <input
                            type="text"
                            id="cpf"
                            name="cpf"
                            maxlength="11"
                            inputmode="numeric"
                            value="<?= valorAntigo("cpf", $old) ?>"
                            placeholder="Somente números"
                            required
                        >

                    </div>

                    <div class="campo">

                        <label for="telefone">Telefone</label>

                        <input
                            type="text"
                            id="telefone"
                            name="telefone"
                            maxlength="15"
                            inputmode="tel"
                            value="<?= valorAntigo("telefone", $old) ?>"
                            placeholder="(00) 00000-0000"
                            required
                        >

                    </div>

                <?php endif; ?>

                <?php if ($tipo === "medico"): ?>

                    <div class="form-grid">

                        <div class="campo">

                            <label for="crm">CRM</label>

                            <input
                                type="text"
                                id="crm"
                                name="crm"
                                maxlength="5"
                                inputmode="numeric"
                                value="<?= valorAntigo("crm", $old) ?>"
                                placeholder="00000"
                                required
                            >

                        </div>

                        <div class="campo">

                            <label for="uf_crm">UF do CRM</label>

                            <select id="uf_crm" name="uf_crm" required>

                                <option value="">Selecione</option>

                                <?php
                                $ufs = [
                                    "AC","AL","AP","AM","BA","CE","DF","ES","GO",
                                    "MA","MT","MS","MG","PA","PB","PR","PE","PI",
                                    "RJ","RN","RS","RO","RR","SC","SP","SE","TO"
                                ];
                                ?>

                                <?php foreach ($ufs as $uf): ?>

                                    <option
                                        value="<?= $uf ?>"
                                        <?= (($old["uf_crm"] ?? "") === $uf) ? "selected" : "" ?>
                                    >
                                        <?= $uf ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                    </div>

                    <div class="campo">

                        <label for="id_especialidade">Especialidade</label>

                        <select
                            id="id_especialidade"
                            name="id_especialidade"
                            required
                        >

                            <option value="">
                                Selecione uma especialidade
                            </option>

                            <?php foreach ($especialidades as $especialidade): ?>

                                <option
                                    value="<?= (int) $especialidade["id_especialidade"] ?>"
                                    <?= ((string)($old["id_especialidade"] ?? "") === (string)$especialidade["id_especialidade"]) ? "selected" : "" ?>
                                >
                                    <?= htmlspecialchars($especialidade["nome_especialidade"]) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                <?php endif; ?>

                <div class="campo">

                    <label for="email">E-mail</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        maxlength="150"
                        value="<?= valorAntigo("email", $old) ?>"
                        autocomplete="email"
                        required
                    >

                </div>

                <div class="form-grid">

                    <div class="campo">

                        <label for="senha">Senha</label>

                        <input
                            type="password"
                            id="senha"
                            name="senha"
                            minlength="6"
                            maxlength="255"
                            autocomplete="new-password"
                            required
                        >

                    </div>

                    <div class="campo">

                        <label for="confirmar_senha">
                            Confirmar senha
                        </label>

                        <input
                            type="password"
                            id="confirmar_senha"
                            name="confirmar_senha"
                            minlength="6"
                            maxlength="255"
                            autocomplete="new-password"
                            required
                        >

                    </div>

                </div>

                <button type="submit" class="btn-principal">
                    Criar conta
                </button>

            </form>

            <div class="auth-footer">

                <a href="cadastro.php">
                    ← Escolher outro tipo de cadastro
                </a>

            </div>

        <?php endif; ?>

        <div class="auth-footer">

            <span>Já possui uma conta?</span>

            <a href="login.php">
                Entrar
            </a>

        </div>

    </section>

</main>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const cpf = document.getElementById("cpf");

    if (cpf) {
        cpf.addEventListener("input", function () {
            this.value = this.value
                .replace(/\D/g, "")
                .slice(0, 11);
        });
    }

    const crm = document.getElementById("crm");

    if (crm) {
        crm.addEventListener("input", function () {
            this.value = this.value
                .replace(/\D/g, "")
                .slice(0, 5);
        });
    }

    const telefone = document.getElementById("telefone");

    if (telefone) {
        telefone.addEventListener("input", function () {

            let valor = this.value.replace(/\D/g, "").slice(0, 11);

            if (valor.length <= 10) {
                valor = valor.replace(
                    /^(\d{2})(\d{4})(\d{0,4}).*/,
                    "($1) $2-$3"
                );
            } else {
                valor = valor.replace(
                    /^(\d{2})(\d{5})(\d{0,4}).*/,
                    "($1) $2-$3"
                );
            }

            this.value = valor;
        });
    }

});
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>