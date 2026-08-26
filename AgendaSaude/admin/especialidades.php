<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo_usuario"] !== "Administrador") {
    header("Location: ../login.php?erro=" . urlencode("Acesso não autorizado."));
    exit;
}

$tituloPagina = "AgendaSaúde | Especialidades";
require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$resultado = mysqli_query(
    $conexao,
    "SELECT id_especialidade, nome_especialidade
     FROM especialidades ORDER BY nome_especialidade"
);
?>

<main class="container">
    <h1 class="page-title">Especialidades</h1>

    <section class="cards">
        <?php while ($e = mysqli_fetch_assoc($resultado)): ?>
            <article class="card">
                <h3>🩺 <?= htmlspecialchars($e["nome_especialidade"]) ?></h3>
                <p>ID: <?= $e["id_especialidade"] ?></p>
            </article>
        <?php endwhile; ?>
    </section>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>