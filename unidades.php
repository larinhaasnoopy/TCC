```php
<?php

session_start();

require_once __DIR__ . "/conexao/conexao.php";

$tituloPagina = "AgendaSaúde | Unidades de Saúde";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/menu.php";

$unidades = $pdo->query("
    SELECT
        id_unidade,
        nome_unidade,
        endereco,
        localizacao,
        telefone
    FROM unidade_saude
    ORDER BY
        localizacao,
        nome_unidade
")->fetchAll(PDO::FETCH_ASSOC);

$servicosPorUnidade = [];

$stmt = $pdo->query("
    SELECT
        us.id_unidade,
        s.tipo_atendimento
    FROM unidade_servico us
    INNER JOIN servico_saude s
        ON s.id_servico = us.id_servico
    ORDER BY
        s.tipo_atendimento
");

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {

    $idUnidade = (int) $linha["id_unidade"];

    $servicosPorUnidade[$idUnidade][] =
        $linha["tipo_atendimento"];
}

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
            REDE DE ATENDIMENTO
        </span>

        <h1 class="page-title">
            Unidades de saúde
        </h1>

        <p>
            Encontre unidades de saúde em São Joaquim da Barra,
            Franca, Orlândia, Uberaba, Ituverava e Ribeirão Preto.
        </p>

    </section>


    <div class="unidades-grid">

        <?php if (!empty($unidades)): ?>

            <?php foreach ($unidades as $unidade): ?>

                <?php
                $idUnidade = (int) $unidade["id_unidade"];
                ?>

                <article class="unidade-card">

                    <span class="unidade-cidade">
                        📍 <?= e($unidade["localizacao"]) ?>
                    </span>

                    <h3>
                        <?= e($unidade["nome_unidade"]) ?>
                    </h3>

                    <p>
                        🏠 <?= e($unidade["endereco"]) ?>
                    </p>

                    <?php if (!empty($unidade["telefone"])): ?>

                        <p>
                            📞 <?= e($unidade["telefone"]) ?>
                        </p>

                    <?php endif; ?>


                    <?php if (!empty($servicosPorUnidade[$idUnidade])): ?>

                        <div class="unidade-servicos">

                            <strong>
                                Serviços disponíveis
                            </strong>

                            <div class="tags-servicos">

                                <?php
                                $servicosExibidos = array_unique(
                                    $servicosPorUnidade[$idUnidade]
                                );
                                ?>

                                <?php foreach ($servicosExibidos as $servico): ?>

                                    <span class="tag-servico">
                                        <?= e($servico) ?>
                                    </span>

                                <?php endforeach; ?>

                            </div>

                        </div>

                    <?php endif; ?>

                </article>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="estado-vazio">

                <h3>
                    Nenhuma unidade encontrada
                </h3>

                <p>
                    Não existem unidades cadastradas no momento.
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


<?php require_once __DIR__ . "/includes/footer.php"; ?>
```
