<?php
session_start();
require_once __DIR__ . "/conexao/conexao.php";

$tituloPagina = "AgendaSaúde | Redefinir senha";
require_once __DIR__ . "/includes/header.php";

$token = $_GET["token"] ?? "";
$erro = $_GET["erro"] ?? "";

$stmt = $pdo->prepare(
    "SELECT * FROM redefinicao_senha
     WHERE token = :token AND usado = 0 AND expira_em >= NOW()
     LIMIT 1"
);
$stmt->execute(["token" => $token]);
$redefinicao = $stmt->fetch();
?>

<main class="auth-page">

    <div class="auth-card">

        <div class="auth-header">
            <span class="auth-icon">🔑</span>
            <h1>Redefinir senha</h1>
            <p>Escolha uma nova senha para sua conta.</p>
        </div>

        <?php if ($erro !== ""): ?>
            <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <?php if (!$redefinicao): ?>

            <div class="alert alert-error">
                Este link é inválido ou já expirou. Solicite uma nova redefinição.
            </div>

            <div class="auth-footer">
                <p><a href="esqueci-senha.php">Solicitar novo link</a></p>
            </div>

        <?php else: ?>

            <form action="controller/redefinir-senha.controller.php" method="POST" class="auth-form">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div class="form-group">
                    <label for="senha">Nova senha</label>
                    <input type="password" id="senha" name="senha" minlength="6" required>
                </div>

                <div class="form-group">
                    <label for="confirmar_senha">Confirmar nova senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" minlength="6" required>
                </div>

                <button type="submit" class="btn-primary btn-full">Redefinir senha</button>
            </form>

        <?php endif; ?>

    </div>

</main>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".auth-form");
    if (!form) return;

    form.addEventListener("submit", function (evento) {
        const senha = document.getElementById("senha").value;
        const confirmar = document.getElementById("confirmar_senha").value;

        if (senha !== confirmar) {
            evento.preventDefault();
            alert("As senhas não coincidem.");
        }
    });
});
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
