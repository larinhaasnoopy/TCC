<?php
session_start();

$tituloPagina = "AgendaSaúde | Esqueceu a senha";

require_once __DIR__ . "/includes/header.php";

$erro = $_GET["erro"] ?? "";
$linkRedefinicao = $_SESSION["link_redefinicao"] ?? "";
unset($_SESSION["link_redefinicao"]);
?>

<main class="auth-page">

    <div class="auth-card">

        <div class="auth-header">
            <span class="auth-icon">🔑</span>
            <h1>Esqueceu a senha?</h1>
            <p>Informe o e-mail cadastrado para redefinir sua senha.</p>
        </div>

        <?php if ($erro !== ""): ?>
            <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if ($linkRedefinicao !== ""): ?>
            <div class="alert alert-success">
                Conta encontrada! Como este é um ambiente local (sem servidor de
                e-mail configurado), o link de redefinição é mostrado aqui mesmo,
                em vez de enviado por e-mail:
                <br><br>
                <a href="<?= htmlspecialchars($linkRedefinicao) ?>">
                    <?= htmlspecialchars($linkRedefinicao) ?>
                </a>
            </div>
        <?php else: ?>

            <form action="controller/esqueci-senha.controller.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email">E-mail cadastrado</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Digite seu e-mail"
                        maxlength="150"
                        required
                    >
                </div>

                <button type="submit" class="btn-primary btn-full">Enviar</button>
            </form>

        <?php endif; ?>

        <div class="auth-footer">
            <p><a href="login.php">← Voltar para o login</a></p>
        </div>

    </div>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
