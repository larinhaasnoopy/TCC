```php
<?php
session_start();

if (
    !isset($_SESSION["id_usuario"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Paciente"
) {
    header(
        "Location: ../login.php?erro=" .
        urlencode("Faça login para continuar.")
    );
    exit;
}

$tituloPagina = "AgendaSaúde | Meu Perfil";

require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$idUsuario = (int) $_SESSION["id_usuario"];

$stmt = $pdo->prepare("
    SELECT
        nome,
        cpf,
        telefone,
        email
    FROM usuario
    WHERE id_usuario = :id_usuario
    LIMIT 1
");

$stmt->execute([
    "id_usuario" => $idUsuario
]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    session_unset();
    session_destroy();

    header(
        "Location: ../login.php?erro=" .
        urlencode("Usuário não encontrado.")
    );
    exit;
}
?>

<main class="container pagina-interna">

    <section class="pagina-cabecalho">
        <span class="pagina-kicker">MINHA CONTA</span>

        <h1 class="page-title">Meu perfil</h1>

        <p>
            Confira os dados cadastrados no AgendaSaúde.
        </p>
    </section>

    <section class="profile-card">

        <div class="form-grid">

            <div class="form-group">
                <label for="nome">Nome</label>

                <input
                    id="nome"
                    type="text"
                    value="<?= htmlspecialchars($usuario["nome"], ENT_QUOTES, "UTF-8") ?>"
                    disabled
                >
            </div>

            <div class="form-group">
                <label for="cpf">CPF</label>

                <input
                    id="cpf"
                    type="text"
                    value="<?= htmlspecialchars($usuario["cpf"], ENT_QUOTES, "UTF-8") ?>"
                    disabled
                >
            </div>

            <div class="form-group">
                <label for="telefone">Telefone</label>

                <input
                    id="telefone"
                    type="text"
                    value="<?= htmlspecialchars($usuario["telefone"], ENT_QUOTES, "UTF-8") ?>"
                    disabled
                >
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>

                <input
                    id="email"
                    type="email"
                    value="<?= htmlspecialchars($usuario["email"], ENT_QUOTES, "UTF-8") ?>"
                    disabled
                >
            </div>

            <div class="form-group">
                <label for="tipo_usuario">Tipo de usuário</label>

                <input
                    id="tipo_usuario"
                    type="text"
                    value="Paciente"
                    disabled
                >
            </div>

        </div>

    </section>

</main>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>
```
