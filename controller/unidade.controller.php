```php
<?php
session_start();

if (
    !isset($_SESSION["id_administrador"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Administrador"
) {
    header("Location: ../login.php?erro=" . urlencode("Acesso não autorizado."));
    exit;
}

require_once __DIR__ . "/../conexao/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin/unidades.php");
    exit;
}

$nome = trim($_POST["nome_unidade"] ?? "");
$endereco = trim($_POST["endereco"] ?? "");
$localizacao = trim($_POST["localizacao"] ?? "");
$telefone = trim($_POST["telefone"] ?? "");

if ($nome === "" || $endereco === "" || $localizacao === "" || $telefone === "") {
    header(
        "Location: ../admin/unidades.php?erro=" .
        urlencode("Preencha todos os campos da unidade.")
    );
    exit;
}

if (mb_strlen($nome) > 150) {
    header(
        "Location: ../admin/unidades.php?erro=" .
        urlencode("O nome da unidade deve ter no máximo 150 caracteres.")
    );
    exit;
}

if (mb_strlen($endereco) > 255) {
    header(
        "Location: ../admin/unidades.php?erro=" .
        urlencode("O endereço deve ter no máximo 255 caracteres.")
    );
    exit;
}

if (mb_strlen($localizacao) > 100) {
    header(
        "Location: ../admin/unidades.php?erro=" .
        urlencode("A localização deve ter no máximo 100 caracteres.")
    );
    exit;
}

if (mb_strlen($telefone) > 15) {
    header(
        "Location: ../admin/unidades.php?erro=" .
        urlencode("O telefone deve ter no máximo 15 caracteres.")
    );
    exit;
}

try {
    $insert = $pdo->prepare("
        INSERT INTO unidade_saude (
            nome_unidade,
            endereco,
            localizacao,
            telefone
        )
        VALUES (
            :nome,
            :endereco,
            :localizacao,
            :telefone
        )
    ");

    $insert->execute([
        "nome" => $nome,
        "endereco" => $endereco,
        "localizacao" => $localizacao,
        "telefone" => $telefone
    ]);

    header(
        "Location: ../admin/unidades.php?sucesso=" .
        urlencode("Unidade cadastrada com sucesso.")
    );
    exit;

} catch (PDOException $e) {
    header(
        "Location: ../admin/unidades.php?erro=" .
        urlencode("Não foi possível cadastrar a unidade.")
    );
    exit;
}
```
