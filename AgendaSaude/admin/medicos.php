<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo_usuario"] !== "Administrador") {
    header("Location: ../login.php?erro=" . urlencode("Acesso não autorizado."));
    exit;
}

$tituloPagina = "AgendaSaúde | Médicos";
require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$especialidades = mysqli_query($conexao, "SELECT * FROM especialidades ORDER BY nome_especialidade");
$unidades = mysqli_query($conexao, "SELECT * FROM unidades_saude ORDER BY nome_unidade");

$medicos = mysqli_query(
    $conexao,
    "SELECT m.id_medico, m.crm, u.nome, u.email,
            e.nome_especialidade, us.nome_unidade
     FROM medicos m
     INNER JOIN usuarios u ON u.id_usuario = m.id_usuario
     INNER JOIN especialidades e ON e.id_especialidade = m.id_especialidade
     INNER JOIN unidades_saude us ON us.id_unidade = m.id_unidade
     ORDER BY u.nome"
);
?>

<main class="container">
    <h1 class="page-title">Médicos</h1>

    <?php if (isset($_GET["erro"])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($_GET["erro"]) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET["sucesso"])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET["sucesso"]) ?></div>
    <?php endif; ?>

    <section class="form-card" style="margin-bottom:25px;">
        <h2 style="color:#1565C0;margin-bottom:20px;">Cadastrar médico</h2>

        <form action="../controller/medico.controller.php" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nome completo</label>
                    <input name="nome" required>
                </div>

                <div class="form-group">
                    <label>CPF</label>
                    <input name="cpf" required>
                </div>

                <div class="form-group">
                    <label>Telefone</label>
                    <input name="telefone">
                </div>

                <div class="form-group">
                    <label>E-mail</label>
                    <input name="email" type="email" required>
                </div>

                <div class="form-group">
                    <label>CRM</label>
                    <input name="crm" required>
                </div>

                <div class="form-group">
                    <label>Especialidade</label>
                    <select name="id_especialidade" required>
                        <option value="">Selecione</option>
                        <?php while ($e = mysqli_fetch_assoc($especialidades)): ?>
                            <option value="<?= $e["id_especialidade"] ?>"><?= htmlspecialchars($e["nome_especialidade"]) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Unidade</label>
                    <select name="id_unidade" required>
                        <option value="">Selecione</option>
                        <?php while ($u = mysqli_fetch_assoc($unidades)): ?>
                            <option value="<?= $u["id_unidade"] ?>"><?= htmlspecialchars($u["nome_unidade"]) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn-primary">Cadastrar médico</button>
            </div>
        </form>
    </section>

    <section class="table-card">
        <table>
            <thead><tr><th>Médico</th><th>CRM</th><th>Especialidade</th><th>Unidade</th></tr></thead>
            <tbody>
            <?php while ($m = mysqli_fetch_assoc($medicos)): ?>
                <tr>
                    <td><?= htmlspecialchars($m["nome"]) ?></td>
                    <td><?= htmlspecialchars($m["crm"]) ?></td>
                    <td><?= htmlspecialchars($m["nome_especialidade"]) ?></td>
                    <td><?= htmlspecialchars($m["nome_unidade"]) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>