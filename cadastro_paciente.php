<?php
$tituloPagina = "AgendaSaúde | Cadastro de Paciente";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/menu.php";
?>

<main class="auth-container">
    <div class="auth-card auth-card-wide">
        <h1>Criar conta de paciente</h1>
        <p class="subtitle">Cadastre seus dados para agendar consultas no AgendaSaúde.</p>

        <?php if (isset($_GET["erro"])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET["erro"]) ?></div>
        <?php endif; ?>

        <form action="controller/cadastro.controller.php" method="POST">
            <div class="form-grid">
                <div class="form-group full">
                    <label for="nome">Nome completo</label>
                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        minlength="3"
                        maxlength="100"
                        pattern="[A-Za-zÀ-ÖØ-öø-ÿ\s]+"
                        title="Apenas letras e espaços (3 a 100 caracteres)"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input
                        type="text"
                        id="cpf"
                        name="cpf"
                        placeholder="000.000.000-00"
                        inputmode="numeric"
                        maxlength="14"
                        title="Informe os 11 dígitos do CPF"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000">
                </div>

                <div class="form-group full">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirmar_senha">Confirmar senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
                </div>
            </div>

            <div class="form-actions">
                <button class="btn-primary" type="submit">Cadastrar</button>
            </div>
        </form>

        <p style="margin-top:18px;">
            <a href="cadastro.php" style="color:#1565C0;font-weight:700;">← Escolher outro tipo de conta</a>
        </p>
    </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const cpf = document.getElementById("cpf");

    cpf.addEventListener("input", function () {
        let valor = cpf.value.replace(/\D/g, "").slice(0, 11);
        valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
        valor = valor.replace(/(\d{3})(\d)/, "$1.$2");
        valor = valor.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        cpf.value = valor;
    });
});
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
