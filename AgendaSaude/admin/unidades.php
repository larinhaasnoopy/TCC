<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo_usuario"] !== "Administrador") {
    header("Location: ../login.php?erro=" . urlencode("Acesso não autorizado."));
    exit;
}

$tituloPagina = "AgendaSaúde | Unidades";
require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$resultado = mysqli_query(
    $conexao,
    "SELECT * FROM unidades_saude ORDER BY nome_unidade"
);
?>

<main class="container">
    <h1 class="page-title">Unidades de saúde</h1>

    <?php if (isset($_GET["erro"])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_GET["erro"]) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET["sucesso"])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET["sucesso"]) ?></div>
    <?php endif; ?>

    <section class="form-card" style="margin-bottom:25px;">
        <h2 style="color:#1565C0;margin-bottom:20px;">Cadastrar unidade</h2>

        <form action="../controller/unidade.controller.php" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nome da unidade</label>
                    <input name="nome_unidade" required>
                </div>

                <div class="form-group">
                    <label>Telefone</label>
                    <input name="telefone">
                </div>

                <div class="form-group full">
                    <label>Endereço</label>
                    <input name="endereco" required>
                </div>

                <div class="form-group">
                    <label>Cidade</label>
                    <input name="cidade" required>
                </div>

                <div class="form-group">
                    <label>Latitude</label>
                    <input name="latitude" type="number" step="any">
                </div>

                <div class="form-group">
                    <label>Longitude</label>
                    <input name="longitude" type="number" step="any">
                </div>
            </div>

            <div class="form-actions">
                <button class="btn-primary">Cadastrar unidade</button>
            </div>
        </form>
    </section>

    <section class="table-card">
        <table>
            <thead><tr><th>Nome</th><th>Endereço</th><th>Cidade</th><th>Telefone</th></tr></thead>
            <tbody>
            <?php while ($u = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?= htmlspecialchars($u["nome_unidade"]) ?></td>
                    <td><?= htmlspecialchars($u["endereco"]) ?></td>
                    <td><?= htmlspecialchars($u["cidade"]) ?></td>
                    <td><?= htmlspecialchars($u["telefone"] ?? "") ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>