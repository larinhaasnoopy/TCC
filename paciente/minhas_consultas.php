```php
<?php

session_start();

if (
    !isset($_SESSION["id_usuario"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Paciente"
) {
    header(
        "Location: ../login.php?erro=" .
        urlencode("Você precisa estar logado como paciente.")
    );
    exit;
}

require_once __DIR__ . "/../conexao/conexao.php";

$tituloPagina = "AgendaSaúde | Minhas Consultas";

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$idUsuario = (int) $_SESSION["id_usuario"];

$stmt = $pdo->prepare("
    SELECT
        agm.id_agendamento,
        agm.data_agendamento,
        agm.horario,
        agm.status,
        agm.lembrete_ativo,

        m.nome AS nome_medico,
        m.crm,
        m.uf_crm,

        e.nome_especialidade,

        s.tipo_atendimento,
        s.duracao,

        u.nome_unidade,
        u.endereco,
        u.localizacao,
        u.telefone

    FROM agendamento agm

    INNER JOIN medico m
        ON m.id_medico = agm.id_medico

    INNER JOIN especialidade_medica e
        ON e.id_especialidade = m.id_especialidade

    INNER JOIN servico_saude s
        ON s.id_servico = agm.id_servico

    INNER JOIN unidade_saude u
        ON u.id_unidade = agm.id_unidade

    WHERE agm.id_usuario = :id_usuario

    ORDER BY
        agm.data_agendamento DESC,
        agm.horario DESC
");

$stmt->execute([
    "id_usuario" => $idUsuario
]);

$consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

function statusClasse(string $status): string
{
    return match ($status) {
        "confirmado" => "status-confirmado",
        "pendente" => "status-pendente",
        "cancelado" => "status-cancelado",
        "em_atendimento" => "status-atendimento",
        "concluido" => "status-concluido",
        default => "status-padrao"
    };
}

function statusTexto(string $status): string
{
    return match ($status) {
        "confirmado" => "Confirmada",
        "pendente" => "Pendente",
        "cancelado" => "Cancelada",
        "em_atendimento" => "Em atendimento",
        "concluido" => "Concluída",
        default => ucfirst($status)
    };
}

function e(string $valor): string
{
    return htmlspecialchars(
        $valor,
        ENT_QUOTES,
        "UTF-8"
    );
}

$mensagemSucesso = trim($_GET["sucesso"] ?? "");
$mensagemErro = trim($_GET["erro"] ?? "");

?>

<main class="container pagina-interna">

    <section class="pagina-cabecalho">

        <span class="pagina-kicker">
            ÁREA DO PACIENTE
        </span>

        <h1 class="page-title">
            Minhas consultas
        </h1>

        <p>
            Consulte seus agendamentos e informações de atendimento.
        </p>

        <div class="pagina-acoes">

            <a
                href="agendar.php"
                class="btn-primary"
            >
                + Nova consulta
            </a>

        </div>

    </section>

    <?php if ($mensagemSucesso !== ""): ?>

        <div class="alert alert-success">
            <?= e($mensagemSucesso) ?>
        </div>

    <?php endif; ?>

    <?php if ($mensagemErro !== ""): ?>

        <div class="alert alert-error">
            <?= e($mensagemErro) ?>
        </div>

    <?php endif; ?>


    <?php if (empty($consultas)): ?>

        <section class="estado-vazio">

            <div class="estado-vazio-icone">
                📅
            </div>

            <h2>
                Nenhuma consulta encontrada
            </h2>

            <p>
                Você ainda não possui consultas agendadas.
            </p>

            <div class="pagina-acoes">

                <a
                    href="agendar.php"
                    class="btn-primary"
                >
                    Agendar consulta
                </a>

            </div>

        </section>

    <?php else: ?>

        <section class="consultas-lista">

            <?php foreach ($consultas as $consulta): ?>

                <?php
                $data = date(
                    "d/m/Y",
                    strtotime($consulta["data_agendamento"])
                );

                $horario = date(
                    "H:i",
                    strtotime($consulta["horario"])
                );
                ?>

                <article class="consulta-card">

                    <div class="consulta-card-topo">

                        <div class="consulta-card-titulo">

                            <span class="consulta-data">
                                <?= e($data) ?>
                                às
                                <?= e($horario) ?>
                            </span>

                            <h2>
                                <?= e($consulta["nome_especialidade"]) ?>
                            </h2>

                        </div>

                        <span
                            class="status-badge <?= e(statusClasse($consulta["status"])) ?>"
                        >
                            <?= e(statusTexto($consulta["status"])) ?>
                        </span>

                    </div>


                    <div class="consulta-card-conteudo">

                        <div class="consulta-info">

                            <span class="consulta-label">
                                Médico
                            </span>

                            <strong>
                                <?= e($consulta["nome_medico"]) ?>
                            </strong>

                            <small>
                                CRM
                                <?= e($consulta["crm"]) ?>

                                <?php if (!empty($consulta["uf_crm"])): ?>
                                    / <?= e($consulta["uf_crm"]) ?>
                                <?php endif; ?>
                            </small>

                        </div>


                        <div class="consulta-info">

                            <span class="consulta-label">
                                Atendimento
                            </span>

                            <strong>
                                <?= e($consulta["tipo_atendimento"]) ?>
                            </strong>

                            <small>
                                Duração:
                                <?= e($consulta["duracao"]) ?>
                            </small>

                        </div>


                        <div class="consulta-info">

                            <span class="consulta-label">
                                Unidade
                            </span>

                            <strong>
                                <?= e($consulta["nome_unidade"]) ?>
                            </strong>

                            <small>
                                <?= e($consulta["localizacao"]) ?>
                            </small>

                        </div>


                        <div class="consulta-info">

                            <span class="consulta-label">
                                Endereço
                            </span>

                            <strong>
                                <?= e($consulta["endereco"]) ?>
                            </strong>

                            <?php if (!empty($consulta["telefone"])): ?>

                                <small>
                                    <?= e($consulta["telefone"]) ?>
                                </small>

                            <?php endif; ?>

                        </div>

                    </div>


                    <div class="consulta-card-rodape">

                        <?php if ((int) $consulta["lembrete_ativo"] === 1): ?>

                            <span class="lembrete-ativo">
                                🔔 Lembrete ativado
                            </span>

                        <?php else: ?>

                            <span class="lembrete-inativo">
                                🔕 Lembrete desativado
                            </span>

                        <?php endif; ?>

                    </div>

                </article>

            <?php endforeach; ?>

        </section>

    <?php endif; ?>

</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
```
