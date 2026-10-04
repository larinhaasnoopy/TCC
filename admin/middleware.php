<?php

/* Impede acesso sem autenticação */
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");


if (!isset($_SESSION["id_usuario"])) {

    header(
        "Location: ../login.php?erro=" .
        urlencode("Faça login para acessar o painel administrativo.")
    );

    exit;
}


/* Verifica o tipo de usuário */
if (
    !isset($_SESSION["tipo_usuario"]) ||
    $_SESSION["tipo_usuario"] !== "Administrador"
) {

    header(
        "Location: ../login.php?erro=" .
        urlencode("Você não possui permissão para acessar esta área.")
    );

    exit;
}