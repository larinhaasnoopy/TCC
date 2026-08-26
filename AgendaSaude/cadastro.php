<?php
$tituloPagina = "AgendaSaúde | Cadastro";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/menu.php";
?>

<main class="auth-page">
    <section class="auth-card">
        <h1>Criar conta</h1>
        <p class="subtitle">Cadastre seus dados para utilizar o AgendaSaúde.</p>

        <?php if (isset($_GET["erro"])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET["erro"]) ?></div>
        <?php endif; ?>

        <form action="controller/cadastro.controller.php" method="POST">
            <div class="form-grid">
                <div class="form-group full">
                    <label for="nome">Nome completo</label>
                    <input type="text" id="nome" name="nome" required>
                </div>

                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" required>
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
            Já possui uma conta?
            <a href="login.php" style="color:#1565C0;font-weight:700;">Fazer login</a>
        </p>
    </section>
</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>