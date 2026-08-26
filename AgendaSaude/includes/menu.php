<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tipo = $_SESSION["tipo_usuario"] ?? "";

$baseUrl = "/AgendaSaude";

?>

<header class="navbar">

    <div class="navbar-container">

        <a class="logo" href="<?= $baseUrl ?>/index.php">
            <span class="logo-icon">✚</span>
            <span>Agenda<span class="logo-destaque">Saude</span></span>
        </a>

        <nav class="nav-links">

            <a href="<?= $baseUrl ?>/index.php">
                Início
            </a>

            <a href="<?= $baseUrl ?>/paciente/agendar.php">
                Agendar
            </a>

            <a href="<?= $baseUrl ?>/paciente/minhas_consultas.php">
                Consultas
            </a>

            <a href="<?= $baseUrl ?>/paciente/perfil.php">
                Perfil
            </a>

            <?php if ($tipo === "Administrador"): ?>

                <a href="<?= $baseUrl ?>/admin/dashboard.php">
                    Administração
                </a>

            <?php endif; ?>

            <?php if (!empty($_SESSION["id_usuario"])): ?>

                <a class="btn-outline" href="<?= $baseUrl ?>/logout.php">
                    Sair
                </a>

            <?php else: ?>

                <a class="btn-outline" href="<?= $baseUrl ?>/login.php">
                    Entrar
                </a>

            <?php endif; ?>

        </nav>

    </div>

</header>