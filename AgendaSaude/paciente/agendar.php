<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php?erro=" . urlencode("Faça login para agendar."));
    exit;
}

$tituloPagina = "AgendaSaúde | Agendar Consulta";

require_once __DIR__ . "/../conexao/conexao.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

/* Busca as especialidades */
$especialidades = mysqli_query(
    $conexao,
    "SELECT id_especialidade, nome_especialidade
     FROM especialidades
     ORDER BY nome_especialidade"
);

/* Busca os médicos cadastrados */
$medicos = mysqli_query(
    $conexao,
    "SELECT 
        m.id_medico,
        u.nome,
        e.id_especialidade,
        e.nome_especialidade,
        us.nome_unidade
     FROM medicos m
     INNER JOIN usuarios u 
        ON u.id_usuario = m.id_usuario
     INNER JOIN especialidades e 
        ON e.id_especialidade = m.id_especialidade
     INNER JOIN unidades_saude us 
        ON us.id_unidade = m.id_unidade
     ORDER BY u.nome"
);

/* Busca somente horários disponíveis */
$horarios = mysqli_query(
    $conexao,
    "SELECT 
        h.id_horario,
        h.id_medico,
        h.data_consulta,
        h.horario,
        u.nome,
        e.nome_especialidade,
        us.nome_unidade
     FROM horarios h
     INNER JOIN medicos m 
        ON m.id_medico = h.id_medico
     INNER JOIN usuarios u 
        ON u.id_usuario = m.id_usuario
     INNER JOIN especialidades e 
        ON e.id_especialidade = m.id_especialidade
     INNER JOIN unidades_saude us 
        ON us.id_unidade = m.id_unidade
     WHERE h.disponivel = 1
       AND h.data_consulta >= CURDATE()
     ORDER BY h.data_consulta, h.horario"
);
?>

<main class="agendar-page">

    <div class="agendar-container">

        <div class="agendar-header">

            <div class="agendar-icon">
                📅
            </div>

            <div>
                <span class="agendar-tag">AGENDA SAÚDE</span>

                <h1>Agendar consulta</h1>

                <p>
                    Escolha a especialidade, o médico e um horário disponível
                    para realizar seu agendamento.
                </p>
            </div>

        </div>


        <?php if (isset($_GET["erro"])): ?>

            <div class="alert alert-error">
                <span>⚠️</span>

                <div>
                    <?= htmlspecialchars($_GET["erro"]) ?>
                </div>
            </div>

        <?php endif; ?>


        <?php if (isset($_GET["sucesso"])): ?>

            <div class="alert alert-success">
                <span>✓</span>

                <div>
                    <?= htmlspecialchars($_GET["sucesso"]) ?>
                </div>
            </div>

        <?php endif; ?>


        <section class="agendar-card">

            <div class="card-title">

                <div class="card-title-icon">
                    🩺
                </div>

                <div>
                    <h2>Dados da consulta</h2>

                    <p>
                        Preencha os campos abaixo para escolher seu atendimento.
                    </p>
                </div>

            </div>


            <form
                action="../controller/consulta.controller.php"
                method="POST"
                class="agendar-form"
            >

                <div class="agendar-grid">


                    <!-- Especialidade -->

                    <div class="form-group">

                        <label for="especialidade">
                            Especialidade
                        </label>

                        <select
                            id="especialidade"
                            name="id_especialidade"
                        >

                            <option value="">
                                Todas as especialidades
                            </option>

                            <?php while ($esp = mysqli_fetch_assoc($especialidades)): ?>

                                <option
                                    value="<?= $esp["id_especialidade"] ?>"
                                >
                                    <?= htmlspecialchars($esp["nome_especialidade"]) ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <!-- Médico -->

                    <div class="form-group">

                        <label for="id_medico">
                            Médico
                        </label>

                        <select
                            id="id_medico"
                            name="id_medico"
                            required
                        >

                            <option value="">
                                Selecione o médico
                            </option>

                            <?php while ($med = mysqli_fetch_assoc($medicos)): ?>

                                <option
                                    value="<?= $med["id_medico"] ?>"
                                    data-especialidade="<?= $med["id_especialidade"] ?>"
                                >
                                    <?= htmlspecialchars($med["nome"]) ?>
                                    —
                                    <?= htmlspecialchars($med["nome_especialidade"]) ?>
                                    —
                                    <?= htmlspecialchars($med["nome_unidade"]) ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>


                    <!-- Horário -->

                    <div class="form-group full">

                        <label for="id_horario">
                            Horário disponível
                        </label>

                        <select
                            id="id_horario"
                            name="id_horario"
                            required
                        >

                            <option value="">
                                Selecione o horário
                            </option>

                            <?php while ($h = mysqli_fetch_assoc($horarios)): ?>

                                <option
                                    value="<?= $h["id_horario"] ?>"
                                    data-medico="<?= $h["id_medico"] ?>"
                                >
                                    <?= date(
                                        "d/m/Y",
                                        strtotime($h["data_consulta"])
                                    ) ?>

                                    às

                                    <?= substr($h["horario"], 0, 5) ?>

                                    —
                                    <?= htmlspecialchars($h["nome"]) ?>

                                    —
                                    <?= htmlspecialchars($h["nome_unidade"]) ?>
                                </option>

                            <?php endwhile; ?>

                        </select>

                        <small class="campo-ajuda">
                            Os horários exibidos correspondem aos atendimentos
                            disponíveis.
                        </small>

                    </div>


                    <!-- Motivo -->

                    <div class="form-group full">

                        <label for="motivo">
                            Motivo da consulta
                            <span>(opcional)</span>
                        </label>

                        <textarea
                            id="motivo"
                            name="motivo"
                            rows="5"
                            maxlength="255"
                            placeholder="Descreva brevemente o motivo da consulta..."
                        ></textarea>

                        <small class="campo-ajuda">
                            Máximo de 255 caracteres.
                        </small>

                    </div>

                </div>


                <div class="agendar-footer">

                    <a
                        href="../index.php"
                        class="btn-cancelar"
                    >
                        Voltar
                    </a>

                    <button
                        type="submit"
                        class="btn-agendar"
                    >
                        <span>✓</span>
                        Confirmar agendamento
                    </button>

                </div>

            </form>

        </section>


        <div class="agendar-info">

            <div class="info-item">
                <span>🔒</span>

                <div>
                    <strong>Agendamento seguro</strong>

                    <p>
                        Seus dados são protegidos pela plataforma.
                    </p>
                </div>
            </div>


            <div class="info-item">
                <span>📅</span>

                <div>
                    <strong>Horários disponíveis</strong>

                    <p>
                        Escolha entre os horários cadastrados.
                    </p>
                </div>
            </div>


            <div class="info-item">
                <span>📋</span>

                <div>
                    <strong>Acompanhe suas consultas</strong>

                    <p>
                        Consulte seus agendamentos pelo seu perfil.
                    </p>
                </div>
            </div>

        </div>

    </div>

</main>


<script>
document.addEventListener("DOMContentLoaded", function () {

    const especialidade = document.getElementById("especialidade");
    const medico = document.getElementById("id_medico");
    const horario = document.getElementById("id_horario");

    /*
     * Guarda as opções originais
     * para podermos filtrar sem perder os dados.
     */
    const medicosOriginais = Array.from(medico.options).slice(1);
    const horariosOriginais = Array.from(horario.options).slice(1);


    function atualizarMedicos() {

        const especialidadeSelecionada = especialidade.value;
        const medicoAtual = medico.value;

        medico.innerHTML = "";

        const primeiraOpcao = document.createElement("option");

        primeiraOpcao.value = "";
        primeiraOpcao.textContent = "Selecione o médico";

        medico.appendChild(primeiraOpcao);


        medicosOriginais.forEach(function (opcao) {

            const especialidadeMedico =
                opcao.getAttribute("data-especialidade");

            if (
                especialidadeSelecionada === "" ||
                especialidadeMedico === especialidadeSelecionada
            ) {

                medico.appendChild(opcao.cloneNode(true));

            }

        });


        if (
            medicoAtual &&
            Array.from(medico.options).some(
                option => option.value === medicoAtual
            )
        ) {
            medico.value = medicoAtual;
        }


        atualizarHorarios();

    }


    function atualizarHorarios() {

        const medicoSelecionado = medico.value;

        const horarioAtual = horario.value;

        horario.innerHTML = "";

        const primeiraOpcao = document.createElement("option");

        primeiraOpcao.value = "";
        primeiraOpcao.textContent = medicoSelecionado
            ? "Selecione o horário"
            : "Selecione primeiro o médico";

        horario.appendChild(primeiraOpcao);


        if (!medicoSelecionado) {
            return;
        }


        horariosOriginais.forEach(function (opcao) {

            const medicoHorario =
                opcao.getAttribute("data-medico");

            if (medicoHorario === medicoSelecionado) {

                horario.appendChild(opcao.cloneNode(true));

            }

        });


        if (
            horarioAtual &&
            Array.from(horario.options).some(
                option => option.value === horarioAtual
            )
        ) {
            horario.value = horarioAtual;
        }

    }


    especialidade.addEventListener(
        "change",
        atualizarMedicos
    );

    medico.addEventListener(
        "change",
        atualizarHorarios
    );

});
</script>


<?php require_once __DIR__ . "/../includes/footer.php"; ?>