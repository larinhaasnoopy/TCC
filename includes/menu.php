<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($baseUrl)) {
    $projectRoot = realpath(__DIR__ . "/..");
    $docRoot = isset($_SERVER["DOCUMENT_ROOT"])
        ? realpath($_SERVER["DOCUMENT_ROOT"])
        : false;

    if ($projectRoot && $docRoot && strpos($projectRoot, $docRoot) === 0) {
        $baseUrl = substr($projectRoot, strlen($docRoot));
        $baseUrl = str_replace("\\", "/", $baseUrl);
        $baseUrl = rtrim($baseUrl, "/");
    } else {
        $baseUrl = "";
    }
}

$tipoUsuario = $_SESSION["tipo_usuario"] ?? "";

$estaLogado =
    !empty($_SESSION["id_usuario"]) ||
    !empty($_SESSION["id_medico"]) ||
    !empty($_SESSION["id_administrador"]);
?>

<nav class="menu-principal">

    <div class="menu-container">

        <a href="<?= $baseUrl ?>/index.php" class="menu-logo">
            <span class="menu-logo-icon">✚</span>
            <span>AgendaSaúde</span>
        </a>

        <div class="menu-links">

            <a href="<?= $baseUrl ?>/index.php">
                Início
            </a>

            <?php if ($tipoUsuario === "Paciente"): ?>

                <a href="<?= $baseUrl ?>/paciente/agendar.php">
                    Agendar
                </a>

                <a href="<?= $baseUrl ?>/paciente/minhas_consultas.php">
                    Minhas consultas
                </a>

                <a href="<?= $baseUrl ?>/paciente/perfil.php">
                    Meu perfil
                </a>

            <?php elseif ($tipoUsuario === "Medico"): ?>

                <a href="<?= $baseUrl ?>/medico/dashboard.php">
                    Minha agenda
                </a>

            <?php elseif ($tipoUsuario === "Administrador"): ?>

                <a href="<?= $baseUrl ?>/admin/dashboard.php">
                    Administração
                </a>

            <?php else: ?>

                <a href="<?= $baseUrl ?>/medicos.php">
                    Médicos
                </a>

                <a href="<?= $baseUrl ?>/unidades.php">
                    Unidades
                </a>

            <?php endif; ?>

        </div>

        <div class="menu-acoes">

            <?php if ($estaLogado): ?>

                <span class="menu-usuario">
                    Olá, <?= htmlspecialchars($_SESSION["nome_usuario"] ?? "Usuário") ?>
                </span>

                <a href="<?= $baseUrl ?>/logout.php" class="menu-sair">
                    Sair
                </a>

            <?php else: ?>

                <a href="<?= $baseUrl ?>/login.php" class="menu-entrar">
                    Entrar
                </a>

                <a href="<?= $baseUrl ?>/cadastro.php" class="menu-cadastrar">
                    Criar conta
                </a>

            <?php endif; ?>

        </div>

    </div>

</nav>