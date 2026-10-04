<?php

session_start();

$tituloPagina = "AgendaSaúde | Escolher Cadastro";

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/menu.php";

?>

<main class="auth-page">

    <section class="auth-card">

        <div class="auth-header">

            <div class="auth-icon">
                🩺
            </div>

            <h1>
                Escolha o tipo de cadastro
            </h1>

            <p>
                Selecione como você deseja acessar o AgendaSaúde.
            </p>

        </div>

        <div class="tipo-escolha">

            <a
                href="cadastro.php?tipo=paciente"
                class="tipo-link"
            >
                <span class="tipo-link-icone">👤</span>

                <span>
                    <strong>Paciente</strong>
                    <small>
                        Agendar e acompanhar consultas
                    </small>
                </span>
            </a>

            <a
                href="cadastro.php?tipo=medico"
                class="tipo-link"
            >
                <span class="tipo-link-icone">🩺</span>

                <span>
                    <strong>Médico</strong>
                    <small>
                        Gerenciar agenda e atendimentos
                    </small>
                </span>
            </a>

            <a
                href="cadastro.php?tipo=administrador"
                class="tipo-link"
            >
                <span class="tipo-link-icone">⚙️</span>

                <span>
                    <strong>Administrador</strong>
                    <small>
                        Administrar o sistema
                    </small>
                </span>
            </a>

        </div>

        <div class="auth-footer">

            <p>
                Já possui uma conta?
                <a href="login.php">
                    Fazer login
                </a>
            </p>

        </div>

    </section>

</main>

<?php require_once __DIR__ . "/includes/footer.php"; ?>