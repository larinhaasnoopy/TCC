```php
<?php

session_start();

require_once __DIR__ . "/conexao/conexao.php";

$tituloPagina = "AgendaSaúde | Nossos Médicos";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/menu.php";

$especialidades = $pdo->query("
    SELECT
        id_especialidade,
        nome_especialidade
    FROM especialidade_medica
    ORDER BY nome_especialidade
")->fetchAll(PDO::FETCH_ASSOC);

$medicos = $pdo->query("
    SELECT
        m.id_medico,
        m.nome,
        m.crm,
        m.uf_crm,
        e.id_especialidade,
        e.nome_especialidade
    FROM medico m
    INNER JOIN especialidade_medica e
        ON e.id_especialidade = m.id_especialidade
    ORDER BY
        e.nome_especialidade,
        m.nome
")->fetchAll(PDO::FETCH_ASSOC);

$especialidadeSelecionada = (int) ($_GET["especialidade"] ?? 0);

function e(string $valor): string
{
    return htmlspecialchars(
        $valor,
        ENT_QUOTES,
        "UTF-8"
    );
}

?>

<main class="container pagina-interna">

    <section class="pagina-cabecalho">

        <span class="pagina-kicker">
            PROFISSIONAIS DE SAÚDE
        </span>

        <h1 class="page-title">
            Nossos médicos
        </h1>

        <p>
            Conheça os profissionais disponíveis por especialidade.
        </p>

    </section>


    <section class="filtro-especialidade">

        <label for="filtro">
            Filtrar por especialidade
        </label>

        <select id="filtro">

            <option value="">
                Todas as especialidades
            </option>

            <?php foreach ($especialidades as $especialidade): ?>

                <option
                    value="<?= (int) $especialidade["id_especialidade"] ?>"
                    <?= $especialidadeSelecionada ===
                        (int) $especialidade["id_especialidade"]
                        ? "selected"
                        : "" ?>
                >
                    <?= e($especialidade["nome_especialidade"]) ?>
                </option>

            <?php endforeach; ?>

        </select>

    </section>


    <div
        class="medicos-grid"
        id="grid-medicos"
    >

        <?php if (!empty($medicos)): ?>

            <?php foreach ($medicos as $medico): ?>

                <article
                    class="medico-card"
                    data-especialidade="<?= (int) $medico["id_especialidade"] ?>"
                >

                    <div class="medico-avatar">
                        🩺
                    </div>

                    <div class="medico-info">

                        <h3>
                            <?= e($medico["nome"]) ?>
                        </h3>

                        <span>
                            <?= e($medico["nome_especialidade"]) ?>
                        </span>

                        <p class="medico-crm">
                            CRM <?= e($medico["crm"]) ?>

                            <?php if (!empty($medico["uf_crm"])): ?>
                                / <?= e($medico["uf_crm"]) ?>
                            <?php endif; ?>
                        </p>

                        <p>
                            Atendimento disponível — confira os horários
                            na tela de agendamento.
                        </p>

                    </div>

                </article>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="estado-vazio">
                <h3>Nenhum médico encontrado</h3>
                <p>
                    Não existem profissionais cadastrados no momento.
                </p>
            </div>

        <?php endif; ?>

    </div>


    <div class="pagina-acoes">

        <a
            class="btn-primary"
            href="paciente/agendar.php"
        >
            Agendar consulta
        </a>

    </div>

</main>


<script>
function aplicarFiltroMedicos() {
    const filtro = document.getElementById("filtro");
    const cards = document.querySelectorAll(
        "#grid-medicos .medico-card"
    );

    if (!filtro) {
        return;
    }

    const valor = filtro.value;

    cards.forEach(function (card) {
        card.style.display =
            !valor || card.dataset.especialidade === valor
                ? "flex"
                : "none";
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const filtro = document.getElementById("filtro");

    if (filtro) {
        filtro.addEventListener(
            "change",
            aplicarFiltroMedicos
        );

        aplicarFiltroMedicos();
    }
});
</script>


<?php require_once __DIR__ . "/includes/footer.php"; ?>
```
