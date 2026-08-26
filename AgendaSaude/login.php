<?php
session_start();

$tituloPagina = "AgendaSaúde | Login";
require_once __DIR__ . "/includes/header.php";
?>

<main class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <span class="auth-icon">🩺</span>
            <h1>Bem-vindo de volta!</h1>
            <p>Faça login para agendar e gerenciar suas consultas.</p>
        </div>

        <?php if (isset($_GET["erro"])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_GET["erro"]) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET["sucesso"])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_GET["sucesso"]) ?>
            </div>
        <?php endif; ?>

        <form action="controller/login.controller.php" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>

            <button type="submit" class="btn-primary btn-full">Entrar</button>
        </form>

        <div class="auth-footer">
            <p>Não possui uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        </div>
    </div>
</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>