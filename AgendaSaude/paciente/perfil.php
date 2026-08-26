<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php?erro=" . urlencode("Faça login para continuar."));
    exit;
}

$tituloPagina = "AgendaSaúde | Meu Perfil";
require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$idUsuario = (int)$_SESSION["id_usuario"];

$stmt = mysqli_prepare(
    $conexao,
    "SELECT nome, cpf, telefone, email, tipo_usuario, data_cadastro
     FROM usuarios WHERE id_usuario = ?"
);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>

<main class="container">
    <h1 class="page-title">Meu perfil</h1>

    <section class="profile-card">
        <div class="form-grid">
            <div class="form-group">
                <label>Nome</label>
                <input value="<?= htmlspecialchars($usuario["nome"]) ?>" disabled>
            </div>

            <div class="form-group">
                <label>CPF</label>
                <input value="<?= htmlspecialchars($usuario["cpf"]) ?>" disabled>
            </div>

            <div class="form-group">
                <label>Telefone</label>
                <input value="<?= htmlspecialchars($usuario["telefone"] ?? "") ?>" disabled>
            </div>

            <div class="form-group">
                <label>E-mail</label>
                <input value="<?= htmlspecialchars($usuario["email"]) ?>" disabled>
            </div>

            <div class="form-group">
                <label>Tipo de usuário</label>
                <input value="<?= htmlspecialchars($usuario["tipo_usuario"]) ?>" disabled>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>