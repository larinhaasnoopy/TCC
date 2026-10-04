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

$tituloPagina = "AgendaSaúde | Usuários";

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$stmt = $pdo->query("
    SELECT
        id_usuario,
        nome,
        cpf,
        email,
        telefone
    FROM usuario
    ORDER BY nome ASC
");

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="pagina-interna">

    <div class="pagina-cabecalho">
        <div>
            <span class="pagina-kicker">Administração</span>
            <h1>Usuários</h1>
            <p>Consulte os pacientes cadastrados no sistema.</p>
        </div>

        <a href="dashboard.php" class="btn-secundario">
            ← Voltar
        </a>
    </div>

    <section class="card-tabela">

        <div class="card-titulo">
            <h2>Pacientes cadastrados</h2>
            <p><?= count($usuarios) ?> usuário(s) encontrado(s).</p>
        </div>

        <?php if (empty($usuarios)): ?>

            <div class="estado-vazio">
                <span>👤</span>
                <p>Nenhum usuário cadastrado.</p>
            </div>

        <?php else: ?>

            <div class="tabela-container">

                <table class="tabela-admin">

                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($usuarios as $usuario): ?>

                            <tr>

                                <td>
                                    <strong>
                                        <?= htmlspecialchars($usuario["nome"]) ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars($usuario["cpf"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($usuario["email"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($usuario["telefone"]) ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </section>

</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>