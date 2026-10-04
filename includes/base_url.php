<?php
/**
 * Calcula o caminho web (base URL) da raiz do projeto de forma dinâmica,
 * comparando o caminho físico deste arquivo com o document root do servidor.
 *
 * Isso evita depender do nome exato da pasta do projeto no htdocs
 * (ex: "AgendaSaude", "HEALTHCARE-PLUS" ou qualquer outro nome),
 * garantindo que o CSS e os links internos funcionem em qualquer
 * página, independentemente da profundidade de pastas (admin/, paciente/...).
 */
if (!isset($baseUrl)) {
    $projectRoot = realpath(__DIR__ . "/..");
    $docRoot = isset($_SERVER["DOCUMENT_ROOT"]) ? realpath($_SERVER["DOCUMENT_ROOT"]) : false;

    if ($projectRoot && $docRoot && strpos($projectRoot, $docRoot) === 0) {
        $baseUrl = substr($projectRoot, strlen($docRoot));
        $baseUrl = str_replace("\\", "/", $baseUrl);
        $baseUrl = rtrim($baseUrl, "/");
    } else {
        /* Fallback: assume que o projeto está na raiz do servidor. */
        $baseUrl = "";
    }
}
