```php
<?php

session_start();

require_once __DIR__ . "/conexao/conexao.php";

$tituloPagina = "AgendaSaúde | Saúde e Tecnologia";

$especialidades = $pdo->query("
    SELECT
        id_especialidade,
        nome_especialidade,
        descricao
    FROM especialidade_medica
    ORDER BY nome_especialidade
    LIMIT 8
")->fetchAll(PDO::FETCH_ASSOC);

$iconesEspecialidade = [
    "Cardiologia" => "❤️",
    "Pediatria" => "👶",
    "Ortopedia" => "🦴",
    "Neurologia" => "🧠",
    "Oftalmologia" => "👁️",
    "Dermatologia" => "🧴",
    "Clínica Geral" => "🩺",
    "Ginecologia e Obstetrícia" => "🤰",
    "Psiquiatria" => "🧩",
    "Endocrinologia" => "⚕️"
];

$tipoUsuario = $_SESSION["tipo_usuario"] ?? "";

$souPaciente =
    isset($_SESSION["id_usuario"]) &&
    $tipoUsuario === "Paciente";

$souMedico =
    isset($_SESSION["id_medico"]) &&
    $tipoUsuario === "Medico";

$souAdministrador =
    isset($_SESSION["id_administrador"]) &&
    $tipoUsuario === "Administrador";

$estaLogado =
    $souPaciente ||
    $souMedico ||
    $souAdministrador;

$proximasConsultas = [];

if ($souPaciente) {

    $stmt = $pdo->prepare("
        SELECT
            ag.id_agendamento,
            ag.data_agendamento,
            ag.horario,
            ag.lembrete_ativo,
            m.nome AS medico
        FROM agendamento ag
        INNER JOIN agenda a
            ON a.id_agenda = ag.id_agenda
        INNER JOIN medico m
            ON m.id_medico = ag.id_medico
        WHERE ag.id_usuario = :id_usuario
          AND ag.status IN ('pendente', 'confirmado')
          AND ag.data_agendamento >= CURDATE()
        ORDER BY
            ag.data_agendamento ASC,
            ag.horario ASC
        LIMIT 3
    ");

    $stmt->execute([
        "id_usuario" => (int) $_SESSION["id_usuario"]
    ]);

    $proximasConsultas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function e(string $valor): string
{
    return htmlspecialchars(
        $valor,
        ENT_QUOTES,
        "UTF-8"
    );
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= e($tituloPagina) ?></title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body>

<header class="topo">

    <div class="container topo-conteudo">

        <a
            href="index.php"
            class="logo"
        >
            <span>✚</span>
            AgendaSaúde
        </a>

        <nav class="menu">

            <a href="index.php">
                Início
            </a>

            <a href="#servicos">
                Serviços
            </a>

            <a href="#especialidades">
                Especialidades
            </a>

            <a href="unidades.php">
                Unidades
            </a>

            <?php if ($souAdministrador): ?>

                <a href="admin/dashboard.php">
                    Administração
                </a>

                <a
                    href="logout.php"
                    class="btn-menu"
                >
                    Sair
                </a>

            <?php elseif ($souMedico): ?>

                <a href="medico/dashboard.php">
                    Minha agenda
                </a>

                <a
                    href="logout.php"
                    class="btn-menu"
                >
                    Sair
                </a>

            <?php elseif ($souPaciente): ?>

                <a href="paciente/dashboard.php">
                    Meu perfil
                </a>

                <a
                    href="logout.php"
                    class="btn-menu"
                >
                    Sair
                </a>

            <?php else: ?>

                <a href="login.php">
                    Entrar
                </a>

                <a
                    href="cadastro.php"
                    class="btn-menu"
                >
                    Criar conta
                </a>

            <?php endif; ?>

        </nav>

    </div>

</header>

<main>

    <?php if (isset($_GET["sucesso"]) || isset($_GET["erro"])): ?>

        <div class="container mensagens-index">

            <?php if (isset($_GET["sucesso"])): ?>

                <div class="alert alert-success">
                    <?= e($_GET["sucesso"]) ?>
                </div>

            <?php endif; ?>

            <?php if (isset($_GET["erro"])): ?>

                <div class="alert alert-error">
                    <?= e($_GET["erro"]) ?>
                </div>

            <?php endif; ?>

        </div>

    <?php endif; ?>


    <section class="hero">

        <div class="container hero-conteudo">

            <div class="hero-texto">

                <span class="tag">
                    SAÚDE + TECNOLOGIA
                </span>

                <h1>
                    Sua saúde conectada
                    ao cuidado que você precisa.
                </h1>

                <p>
                    Encontre profissionais, especialidades,
                    unidades de saúde e horários disponíveis
                    para agendar sua consulta de forma rápida,
                    segura e prática.
                </p>

                <div class="hero-botoes">

                    <a
                        href="paciente/agendar.php"
                        class="btn-principal"
                    >
                        Agendar consulta
                    </a>

                    <a
                        href="#servicos"
                        class="btn-secundario"
                    >
                        Conhecer serviços
                    </a>

                </div>

            </div>

            <div class="hero-imagem">

                <div class="imagem-medico">

                    <div class="icone-medico">
                        🩺
                    </div>

                    <div class="circulo"></div>

                </div>

            </div>

        </div>

    </section>


    <section
        class="servicos"
        id="servicos"
    >

        <div class="container">

            <div class="titulo-secao">

                <span>
                    O QUE OFERECEMOS
                </span>

                <h2>
                    Nossos serviços
                </h2>

                <p>
                    Tudo o que você precisa para cuidar
                    da sua saúde em um só lugar.
                </p>

            </div>

            <div class="cards-servicos">

                <div class="card-servico">

                    <div class="icone-card">
                        🩺
                    </div>

                    <h3>
                        Consultas Médicas
                    </h3>

                    <p>
                        Encontre médicos e profissionais
                        de diferentes especialidades.
                    </p>

                    <a href="medicos.php">
                        Ver médicos →
                    </a>

                </div>


                <div class="card-servico">

                    <div class="icone-card">
                        📅
                    </div>

                    <h3>
                        Agendamento
                    </h3>

                    <p>
                        Escolha a especialidade, profissional,
                        unidade, data e horário.
                    </p>

                    <a href="paciente/agendar.php">
                        Agendar agora →
                    </a>

                </div>


                <div class="card-servico">

                    <div class="icone-card">
                        🏥
                    </div>

                    <h3>
                        Unidades de Saúde
                    </h3>

                    <p>
                        Consulte informações sobre unidades,
                        endereços e serviços disponíveis
                        na região.
                    </p>

                    <a href="unidades.php">
                        Ver unidades →
                    </a>

                </div>


                <div class="card-servico">

                    <div class="icone-card">
                        🔔
                    </div>

                    <h3>
                        Próximas consultas
                    </h3>

                    <?php if ($souPaciente && !empty($proximasConsultas)): ?>

                        <div class="lembrete-lista">

                            <?php foreach ($proximasConsultas as $consulta): ?>

                                <div class="lembrete-item">

                                    <div class="lembrete-detalhes">

                                        <strong>
                                            <?= date(
                                                "d/m/Y",
                                                strtotime(
                                                    $consulta["data_agendamento"]
                                                )
                                            ) ?>

                                            às

                                            <?= substr(
                                                $consulta["horario"],
                                                0,
                                                5
                                            ) ?>
                                        </strong>

                                        <span>
                                            Dr(a).
                                            <?= e($consulta["medico"]) ?>
                                        </span>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php elseif ($souPaciente): ?>

                        <p>
                            Você não possui consultas futuras
                            programadas.
                        </p>

                    <?php else: ?>

                        <p>
                            Consulte suas próximas consultas
                            e acompanhe suas datas e horários.
                        </p>

                    <?php endif; ?>

                    <a
                        href="<?= $souPaciente
                            ? "paciente/minhas_consultas.php"
                            : "login.php" ?>"
                    >
                        <?= $souPaciente
                            ? "Minhas consultas →"
                            : "Entrar →" ?>
                    </a>

                </div>

            </div>

        </div>

    </section>


    <section
        class="especialidades"
        id="especialidades"
    >

        <div class="container">

            <div class="titulo-secao">

                <span>
                    ENCONTRE O PROFISSIONAL IDEAL
                </span>

                <h2>
                    Especialidades médicas
                </h2>

                <p>
                    Escolha a especialidade que você precisa
                    e encontre profissionais disponíveis.
                </p>

            </div>

            <div class="grid-especialidades">

                <?php foreach ($especialidades as $especialidade): ?>

                    <a
                        href="medicos.php?especialidade=<?= (int) $especialidade["id_especialidade"] ?>"
                        class="especialidade"
                    >

                        <span class="icone-especialidade">
                            <?= $iconesEspecialidade[
                                $especialidade["nome_especialidade"]
                            ] ?? "🩺" ?>
                        </span>

                        <strong>
                            <?= e($especialidade["nome_especialidade"]) ?>
                        </strong>

                        <span class="especialidade-desc">
                            <?= e($especialidade["descricao"] ?? "") ?>
                        </span>

                    </a>

                <?php endforeach; ?>

                <a
                    href="medicos.php"
                    class="especialidade"
                >

                    <span class="icone-especialidade">
                        ➕
                    </span>

                    <strong>
                        Ver todas
                    </strong>

                    <span class="especialidade-desc">
                        Veja a lista completa de médicos
                        e especialidades.
                    </span>

                </a>

            </div>

        </div>

    </section>


    <section
        class="unidades"
        id="unidades"
    >

        <div class="container unidades-conteudo">

            <div>

                <span class="tag">
                    ENCONTRE UMA UNIDADE
                </span>

                <h2>
                    Atendimento perto de você
                </h2>

                <p>
                    Consulte as unidades de saúde disponíveis
                    em São Joaquim da Barra, Franca, Orlândia,
                    Uberaba, Ituverava e Ribeirão Preto.
                </p>

                <a
                    href="unidades.php"
                    class="btn-principal"
                >
                    Encontrar unidade
                </a>

            </div>

            <div class="mapa">

                <div class="mapa-icone">
                    📍
                </div>

                <h3>
                    Localização das unidades
                </h3>

                <p>
                    Consulte endereços e informações
                    das unidades cadastradas.
                </p>

            </div>

        </div>

    </section>


    <section class="como-funciona">

        <div class="container">

            <div class="titulo-secao">

                <span>
                    SIMPLES E RÁPIDO
                </span>

                <h2>
                    Como funciona?
                </h2>

            </div>

            <div class="passos">

                <div class="passo">

                    <div class="numero">
                        01
                    </div>

                    <h3>
                        Crie sua conta
                    </h3>

                    <p>
                        Cadastre seus dados para acessar
                        a plataforma.
                    </p>

                </div>

                <div class="passo">

                    <div class="numero">
                        02
                    </div>

                    <h3>
                        Encontre seu atendimento
                    </h3>

                    <p>
                        Escolha especialidade, médico,
                        unidade e horário.
                    </p>

                </div>

                <div class="passo">

                    <div class="numero">
                        03
                    </div>

                    <h3>
                        Agende sua consulta
                    </h3>

                    <p>
                        Confirme o horário e acompanhe
                        seu agendamento.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <section class="cta">

        <div class="container cta-conteudo">

            <div>

                <span>
                    CUIDE DA SUA SAÚDE
                </span>

                <h2>
                    Pronto para agendar sua consulta?
                </h2>

                <p>
                    Encontre o atendimento que você precisa
                    de forma simples, rápida e segura.
                </p>

            </div>

            <a
                href="paciente/agendar.php"
                class="btn-branco"
            >
                Agendar consulta
            </a>

        </div>

    </section>

</main>


<footer class="rodape">

    <div class="container rodape-conteudo">

        <div>

            <a
                href="index.php"
                class="logo rodape-logo"
            >
                <span>✚</span>
                AgendaSaúde
            </a>

            <p>
                Saúde conectada ao cuidado que você precisa.
            </p>

        </div>


        <div>

            <h3>
                Plataforma
            </h3>

            <a href="index.php">
                Início
            </a>

            <a href="paciente/agendar.php">
                Agendar consulta
            </a>

            <a href="login.php">
                Entrar
            </a>

        </div>


        <div>

            <h3>
                Atendimento
            </h3>

            <a href="#especialidades">
                Especialidades
            </a>

            <a href="unidades.php">
                Unidades
            </a>

            <a href="#servicos">
                Serviços
            </a>

        </div>


        <div>

            <h3>
                AgendaSaúde
            </h3>

            <p>
                Sistema de auxílio ao acesso
                e agendamento de serviços de saúde.
            </p>

        </div>

    </div>


    <div class="rodape-final">

        <p>
            © 2026 AgendaSaúde. Todos os direitos reservados.
        </p>

    </div>

</footer>

</body>
</html>
```
