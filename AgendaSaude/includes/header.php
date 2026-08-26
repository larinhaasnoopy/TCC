<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Caminho principal do projeto */
$baseUrl = "/AgendaSaude";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= isset($tituloPagina) ? htmlspecialchars($tituloPagina) : "AgendaSaude" ?>
    </title>

    <!-- CSS principal -->
    <link rel="stylesheet" href="<?= $baseUrl ?>/css/style.css">

</head>

<body>