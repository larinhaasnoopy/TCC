<?php
$tituloPagina = "AgendaSaúde | Cadastro de Administrador";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/menu.php";
?>

<main class="auth-container">
    <div class="auth-card auth-card-wide">
        <h1>Cadastro de administrador</h1>
        <p class="subtitle">Acesso restrito à gestão do sistema.</p>

        <div class="aviso-card">
            ⚠️ Por segurança, contas de administrador não podem ser criadas
            diretamente pelo site. Elas são cadastradas internamente pela
            equipe responsável pelo AgendaSaúde.
        </div>

        <p style="margin-top:18px;">
            <a href="cadastro.php" style="color:#1565C0;font-weight:700;">← Escolher outro tipo de conta</a>
        </p>
    </div>
</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
