<?php
session_start();

if (
    !isset($_SESSION["id_administrador"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Administrador"
) {
    header("Location: ../login.php?erro=" . urlencode("Você não possui permissão para acessar esta área."));
    exit;
}

require_once __DIR__ . "/../conexao/conexao.php";

$tituloPagina = "AgendaSaúde | Unidades";

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$erro = $_GET["erro"] ?? "";
$sucesso = $_GET["sucesso"] ?? "";

$stmt = $pdo->query("
    SELECT
        id_unidade,
        nome_unidade,
        endereco,
        localizacao,
        telefone
    FROM unidade_saude
    ORDER BY nome_unidade ASC
");

$unidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="pagina-interna">

    <div class="pagina-cabecalho">
        <div>
            <span class="pagina-kicker">Administração</span>
            <h1>Unidades de Saúde</h1>
            <p>Cadastre e consulte as unidades disponíveis no sistema.</p>
        </div>

        <a href="dashboard.php" class="btn-secundario">
            ← Voltar
        </a>
    </div>

    <?php if ($erro !== ""): ?>
        <div class="mensagem mensagem-erro">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>

    <?php if ($sucesso !== ""): ?>
        <div class="mensagem mensagem-sucesso">
            <?= htmlspecialchars($sucesso) ?>
        </div>
    <?php endif; ?>

    <section class="admin-grid">

        <div class="card-formulario">

            <div class="card-titulo">
                <h2>Nova unidade</h2>
                <p>Preencha os dados da unidade de saúde.</p>
            </div>

            <form action="../controller/unidade.controller.php" method="POST">

                <div class="campo">
                    <label for="nome_unidade">Nome da unidade</label>
                    <input
                        type="text"
                        id="nome_unidade"
                        name="nome_unidade"
                        maxlength="150"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="telefone">Telefone</label>
                    <input
                        type="text"
                        id="telefone"
                        name="telefone"
                        maxlength="15"
                        placeholder="(00) 00000-0000"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="endereco">Endereço</label>
                    <input
                        type="text"
                        id="endereco"
                        name="endereco"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="localizacao">Localização</label>
                    <input
                        type="text"
                        id="localizacao"
                        name="localizacao"
                        maxlength="100"
                        placeholder="Cidade - UF"
                        required
                    >
                </div>

                <button type="submit" class="btn-principal">
                    Cadastrar unidade
                </button>

            </form>
        </div>

        <div class="card-tabela">

            <div class="card-titulo">
                <h2>Unidades cadastradas</h2>
                <p><?= count($unidades) ?> unidade(s) encontrada(s).</p>
            </div>

            <?php if (empty($unidades)): ?>

                <div class="estado-vazio">
                    <span>🏥</span>
                    <p>Nenhuma unidade cadastrada.</p>
                </div>

            <?php else: ?>

                <div class="tabela-container">

                    <table class="tabela-admin">

                        <thead>
                            <tr>
                                <th>Unidade</th>
                                <th>Localização</th>
                                <th>Telefone</th>
                                <th>Endereço</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($unidades as $unidade): ?>

                                <tr>

                                    <td>
                                        <strong>
                                            <?= htmlspecialchars($unidade["nome_unidade"]) ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($unidade["localizacao"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($unidade["telefone"]) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($unidade["endereco"]) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </section>

</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>