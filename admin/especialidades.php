<?php

session_start();

if (
    !isset($_SESSION["id_administrador"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Administrador"
) {
    header(
        "Location: ../login.php?erro=" .
        urlencode("Você não possui permissão para acessar esta área.")
    );
    exit;
}

require_once __DIR__ . "/../conexao/conexao.php";

$tituloPagina = "AgendaSaúde | Especialidades";

$stmt = $pdo->query("
    SELECT
        id_especialidade,
        nome_especialidade,
        descricao
    FROM especialidade_medica
    ORDER BY nome_especialidade ASC
");

$especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";
?>

<main class="container">

    <h1 class="page-title">Especialidades Médicas</h1>

    <?php if (empty($especialidades)): ?>

        <section class="card">
            <p>Nenhuma especialidade cadastrada.</p>
        </section>

    <?php else: ?>

        <section class="grid-especialidades">

            <?php foreach ($especialidades as $especialidade): ?>

                <article class="especialidade">

                    <strong>
                        <?= htmlspecialchars(
                            $especialidade["nome_especialidade"]
                        ) ?>
                    </strong>

                    <?php if (!empty($especialidade["descricao"])): ?>

                        <p class="especialidade-desc">
                            <?= htmlspecialchars(
                                $especialidade["descricao"]
                            ) ?>
                        </p>

                    <?php endif; ?>

                </article>

            <?php endforeach; ?>

        </section>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>