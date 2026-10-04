```php
<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($baseUrl)) {

    $raizProjeto = realpath(__DIR__ . "/..");
    $raizServidor = realpath($_SERVER["DOCUMENT_ROOT"] ?? "");

    if (
        $raizProjeto &&
        $raizServidor &&
        strpos($raizProjeto, $raizServidor) === 0
    ) {
        $baseUrl = substr(
            $raizProjeto,
            strlen($raizServidor)
        );

        $baseUrl = str_replace(
            "\\",
            "/",
            $baseUrl
        );

        $baseUrl = rtrim(
            $baseUrl,
            "/"
        );

    } else {
        $baseUrl = "";
    }
}

$titulo = $tituloPagina ?? "AgendaSaúde";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="AgendaSaúde - plataforma para consulta e agendamento de serviços de saúde."
    >

    <title>
        <?= htmlspecialchars(
            $titulo,
            ENT_QUOTES,
            "UTF-8"
        ) ?>
    </title>

    <link
        rel="stylesheet"
        href="<?= htmlspecialchars(
            $baseUrl,
            ENT_QUOTES,
            "UTF-8"
        ) ?>/css/style.css"
    >

</head>

<body>
```
