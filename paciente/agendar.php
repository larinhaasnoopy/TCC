<?php
session_start();

if (
    !isset($_SESSION["id_usuario"]) ||
    ($_SESSION["tipo_usuario"] ?? "") !== "Paciente"
) {
    header("Location: ../login.php?erro=" . urlencode("Você precisa estar logado como paciente."));
    exit;
}

require_once __DIR__ . "/../conexao/conexao.php";

$tituloPagina = "AgendaSaúde | Agendar Consulta";

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/menu.php";

$stmt = $pdo->query("
    SELECT
        id_especialidade,
        nome_especialidade
    FROM especialidade_medica
    ORDER BY nome_especialidade ASC
");

$especialidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("
    SELECT
        id_unidade,
        nome_unidade,
        localizacao
    FROM unidade_saude
    ORDER BY nome_unidade ASC
");

$unidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="pagina-interna">

    <div class="pagina-cabecalho">

        <div>
            <span class="pagina-kicker">Paciente</span>

            <h1>Agendar consulta</h1>

            <p>
                Escolha a especialidade, o médico, a unidade e um horário disponível.
            </p>
        </div>

        <a
            href="minhas_consultas.php"
            class="btn-secundario"
        >
            Minhas consultas
        </a>

    </div>

    <section class="card-formulario agendamento-formulario">

        <div class="card-titulo">

            <h2>Escolha seu atendimento</h2>

            <p>
                Os horários são disponibilizados pelos profissionais cadastrados.
            </p>

        </div>

        <form
            action="../controller/agendamento.controller.php"
            method="POST"
            id="formAgendamento"
        >

            <div class="campo">

                <label for="id_especialidade">
                    Especialidade
                </label>

                <select
                    id="id_especialidade"
                    name="id_especialidade"
                    required
                >

                    <option value="">
                        Selecione uma especialidade
                    </option>

                    <?php foreach ($especialidades as $especialidade): ?>

                        <option
                            value="<?= (int) $especialidade["id_especialidade"] ?>"
                        >
                            <?= htmlspecialchars($especialidade["nome_especialidade"]) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="campo">

                <label for="id_medico">
                    Médico
                </label>

                <select
                    id="id_medico"
                    name="id_medico"
                    required
                    disabled
                >

                    <option value="">
                        Primeiro selecione uma especialidade
                    </option>

                </select>

            </div>

            <div class="campo">

                <label for="id_unidade">
                    Unidade de saúde
                </label>

                <select
                    id="id_unidade"
                    name="id_unidade"
                    required
                    disabled
                >

                    <option value="">
                        Selecione uma unidade
                    </option>

                    <?php foreach ($unidades as $unidade): ?>

                        <option
                            value="<?= (int) $unidade["id_unidade"] ?>"
                        >
                            <?= htmlspecialchars($unidade["nome_unidade"]) ?>
                            —
                            <?= htmlspecialchars($unidade["localizacao"]) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="campo">

                <label for="data">
                    Data
                </label>

                <select
                    id="data"
                    name="data"
                    required
                    disabled
                >

                    <option value="">
                        Selecione médico e unidade primeiro
                    </option>

                </select>

            </div>

            <div class="campo">

                <label for="id_agenda">
                    Horário
                </label>

                <select
                    id="id_agenda"
                    name="id_agenda"
                    required
                    disabled
                >

                    <option value="">
                        Selecione uma data primeiro
                    </option>

                </select>

            </div>

            <div class="campo">

                <label for="tipo_atendimento">
                    Tipo de atendimento
                </label>

                <select
                    id="tipo_atendimento"
                    name="tipo_atendimento"
                    disabled
                >

                    <option value="">
                        Será definido pelo horário
                    </option>

                </select>

            </div>

            <div
                id="mensagemAgendamento"
                class="mensagem"
                style="display: none;"
            ></div>

            <button
                type="submit"
                class="btn-principal"
                id="btnAgendar"
                disabled
            >
                Confirmar agendamento
            </button>

        </form>

    </section>

</main>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const especialidade = document.getElementById("id_especialidade");
    const medico = document.getElementById("id_medico");
    const unidade = document.getElementById("id_unidade");
    const data = document.getElementById("data");
    const agenda = document.getElementById("id_agenda");
    const tipoAtendimento = document.getElementById("tipo_atendimento");
    const botao = document.getElementById("btnAgendar");
    const mensagem = document.getElementById("mensagemAgendamento");

    function limparMensagem() {
        mensagem.style.display = "none";
        mensagem.textContent = "";
        mensagem.className = "mensagem";
    }

    function mostrarMensagem(texto) {
        mensagem.textContent = texto;
        mensagem.style.display = "block";
        mensagem.className = "mensagem mensagem-erro";
    }

    function limparMedico() {
        medico.innerHTML = `
            <option value="">
                Primeiro selecione uma especialidade
            </option>
        `;

        medico.disabled = true;
    }

    function limparDatas() {
        data.innerHTML = `
            <option value="">
                Selecione médico e unidade primeiro
            </option>
        `;

        data.disabled = true;
    }

    function limparHorarios() {
        agenda.innerHTML = `
            <option value="">
                Selecione uma data primeiro
            </option>
        `;

        agenda.disabled = true;

        tipoAtendimento.innerHTML = `
            <option value="">
                Será definido pelo horário
            </option>
        `;

        tipoAtendimento.disabled = true;

        botao.disabled = true;
    }

    especialidade.addEventListener("change", async function () {

        limparMensagem();
        limparDatas();
        limparHorarios();

        medico.innerHTML = `
            <option value="">
                Carregando médicos...
            </option>
        `;

        medico.disabled = true;

        if (!this.value) {
            limparMedico();
            return;
        }

        try {

            const resposta = await fetch(
                "../controller/medicos.ajax.php?id_especialidade=" +
                encodeURIComponent(this.value)
            );

            const dados = await resposta.json();

            if (!dados.sucesso) {
                mostrarMensagem(
                    dados.mensagem || "Não foi possível carregar os médicos."
                );

                limparMedico();
                return;
            }

            medico.innerHTML = `
                <option value="">
                    Selecione um médico
                </option>
            `;

            if (!dados.medicos || dados.medicos.length === 0) {

                medico.innerHTML = `
                    <option value="">
                        Nenhum médico disponível
                    </option>
                `;

                medico.disabled = true;
                return;
            }

            dados.medicos.forEach(function (item) {

                const option = document.createElement("option");

                option.value = item.id_medico;

                option.textContent =
                    item.nome +
                    " — CRM " +
                    item.crm +
                    "/" +
                    item.uf_crm;

                medico.appendChild(option);

            });

            medico.disabled = false;

        } catch (erro) {

            mostrarMensagem(
                "Não foi possível carregar os médicos."
            );

            limparMedico();
        }
    });

    medico.addEventListener("change", function () {

        limparMensagem();
        limparDatas();
        limparHorarios();

        if (!this.value || !unidade.value) {
            return;
        }

        carregarDatas();
    });

    unidade.addEventListener("change", function () {

        limparMensagem();
        limparDatas();
        limparHorarios();

        if (!this.value || !medico.value) {
            return;
        }

        carregarDatas();
    });

    async function carregarDatas() {

        data.innerHTML = `
            <option value="">
                Carregando datas...
            </option>
        `;

        data.disabled = true;

        try {

            const url =
                "../controller/horarios.ajax.php" +
                "?id_medico=" +
                encodeURIComponent(medico.value) +
                "&id_unidade=" +
                encodeURIComponent(unidade.value);

            const resposta = await fetch(url);

            const dados = await resposta.json();

            if (!dados.sucesso) {
                mostrarMensagem(
                    dados.mensagem || "Não foi possível carregar as datas."
                );

                limparDatas();
                return;
            }

            data.innerHTML = `
                <option value="">
                    Selecione uma data
                </option>
            `;

            if (!dados.datas || dados.datas.length === 0) {

                data.innerHTML = `
                    <option value="">
                        Nenhuma data disponível
                    </option>
                `;

                data.disabled = true;
                return;
            }

            dados.datas.forEach(function (item) {

                const option = document.createElement("option");

                option.value = item;

                const partes = item.split("-");

                if (partes.length === 3) {

                    option.textContent =
                        partes[2] +
                        "/" +
                        partes[1] +
                        "/" +
                        partes[0];

                } else {

                    option.textContent = item;

                }

                data.appendChild(option);

            });

            data.disabled = false;

        } catch (erro) {

            mostrarMensagem(
                "Não foi possível carregar as datas."
            );

            limparDatas();
        }
    }

    data.addEventListener("change", async function () {

        limparMensagem();
        limparHorarios();

        if (!this.value) {
            return;
        }

        agenda.innerHTML = `
            <option value="">
                Carregando horários...
            </option>
        `;

        agenda.disabled = true;

        try {

            const url =
                "../controller/horarios.ajax.php" +
                "?id_medico=" +
                encodeURIComponent(medico.value) +
                "&id_unidade=" +
                encodeURIComponent(unidade.value) +
                "&data=" +
                encodeURIComponent(this.value);

            const resposta = await fetch(url);

            const dados = await resposta.json();

            if (!dados.sucesso) {

                mostrarMensagem(
                    dados.mensagem || "Não foi possível carregar os horários."
                );

                limparHorarios();
                return;
            }

            agenda.innerHTML = `
                <option value="">
                    Selecione um horário
                </option>
            `;

            if (!dados.horarios || dados.horarios.length === 0) {

                agenda.innerHTML = `
                    <option value="">
                        Nenhum horário disponível
                    </option>
                `;

                agenda.disabled = true;
                return;
            }

            dados.horarios.forEach(function (item) {

                const option = document.createElement("option");

                option.value = item.id_agenda;

                option.dataset.tipo = item.tipo_atendimento || "";

                option.dataset.duracao = item.duracao || "";

                option.textContent =
                    item.horario_disponivel.substring(0, 5) +
                    " — " +
                    item.tipo_atendimento;

                agenda.appendChild(option);

            });

            agenda.disabled = false;

        } catch (erro) {

            mostrarMensagem(
                "Não foi possível carregar os horários."
            );

            limparHorarios();
        }
    });

    agenda.addEventListener("change", function () {

        const opcao = this.options[this.selectedIndex];

        if (!this.value || !opcao) {

            tipoAtendimento.innerHTML = `
                <option value="">
                    Será definido pelo horário
                </option>
            `;

            tipoAtendimento.disabled = true;
            botao.disabled = true;

            return;
        }

        const tipo = opcao.dataset.tipo || "";

        tipoAtendimento.innerHTML = "";

        const novaOpcao = document.createElement("option");

        novaOpcao.value = tipo;
        novaOpcao.textContent = tipo;

        tipoAtendimento.appendChild(novaOpcao);

        tipoAtendimento.disabled = false;

        botao.disabled = false;
    });

});
</script>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>