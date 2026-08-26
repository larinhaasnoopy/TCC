<?php
session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["tipo_usuario"] !== "Administrador") {
    header("Location: ../login.php?erro=" . urlencode("Acesso não autorizado."));
    exit;
}

$tituloPagina = "AgendaSaúde | Usuários";
require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$resultado = mysqli_query(
    $conexao,
    "SELECT id_usuario, nome, cpf, email, telefone, tipo_usuario, data_cadastro
     FROM usuarios ORDER BY id_usuario DESC"
);
?>

<main class="container">
    <h1 class="page-title">Usuários cadastrados</h1>

    <section class="table-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Nome</th><th>CPF</th><th>E-mail</th><th>Tipo</th><th>Cadastro</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($u = mysqli_fetch_assoc($resultado)): ?>
                <tr>
                    <td><?= $u["id_usuario"] ?></td>
                    <td><?= htmlspecialchars($u["nome"]) ?></td>
                    <td><?= htmlspecialchars($u["cpf"]) ?></td>
                    <td><?= htmlspecialchars($u["email"]) ?></td>
                    <td><span class="badge"><?= htmlspecialchars($u["tipo_usuario"]) ?></span></td>
                    <td><?= date("d/m/Y", strtotime($u["data_cadastro"])) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>