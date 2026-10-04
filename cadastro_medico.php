<?php
$tituloPagina = "AgendaSaúde | Cadastro de Médico";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/menu.php";
?>

<main class="auth-container">
    <div class="auth-card auth-card-wide">
        <h1>Cadastro de médico</h1>
        <p class="subtitle">Conta de acesso para profissionais de saúde.</p>

        <div class="aviso-card">
            ⚠️ O cadastro de médicos é feito pela equipe administrativa do AgendaSaúde,
            que vincula o profissional à especialidade correta.
            Se você é médico e deseja atender pela plataforma, entre em contato
            com o administrador da sua unidade de saúde.
        </div>

        <p style="margin-top:18px;">
            <a href="cadastro.php" style="color:#1565C0;font-weight:700;">← Escolher outro tipo de conta</a>
        </p>
    </div>
</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
