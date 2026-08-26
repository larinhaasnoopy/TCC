<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AgendaSaúde | Saúde e Tecnologia</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- =====================================================
         CABEÇALHO
    ====================================================== -->

    <header class="topo">

        <div class="container topo-conteudo">

            <a href="index.php" class="logo">
                <span>✚</span> AgendaSaúde
            </a>

            <nav class="menu">

                <a href="index.php">Início</a>

                <a href="#servicos">Serviços</a>

                <a href="#especialidades">Especialidades</a>

                <a href="#unidades">Unidades</a>

                <?php if (isset($_SESSION["id_usuario"])): ?>

                    <a href="paciente/dashboard.php">Meu perfil</a>

                    <a href="logout.php" class="btn-menu">
                        Sair
                    </a>

                <?php else: ?>

                    <a href="login.php">Entrar</a>

                    <a href="cadastro.php" class="btn-menu">
                        Criar conta
                    </a>

                <?php endif; ?>

            </nav>

        </div>

    </header>


    <!-- =====================================================
         BANNER PRINCIPAL
    ====================================================== -->

    <main>

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

                        <a href="paciente/agendar.php" class="btn-principal">
                            Agendar consulta
                        </a>

                        <a href="#servicos" class="btn-secundario">
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


        <!-- =====================================================
             SERVIÇOS
        ====================================================== -->

        <section class="servicos" id="servicos">

            <div class="container">

                <div class="titulo-secao">

                    <span>O QUE OFERECEMOS</span>

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

                        <a href="paciente/agendar.php">
                            Agendar consulta →
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
                            endereços e serviços disponíveis.
                        </p>

                        <a href="#unidades">
                            Ver unidades →
                        </a>

                    </div>


                    <div class="card-servico">

                        <div class="icone-card">
                            🔔
                        </div>

                        <h3>
                            Lembretes
                        </h3>

                        <p>
                            Receba confirmações e lembretes
                            dos seus agendamentos.
                        </p>

                        <a href="paciente/minhas_consultas.php">
                            Minhas consultas →
                        </a>

                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             ESPECIALIDADES
        ====================================================== -->

        <section class="especialidades" id="especialidades">

            <div class="container">

                <div class="titulo-secao">

                    <span>ENCONTRE O PROFISSIONAL IDEAL</span>

                    <h2>
                        Especialidades médicas
                    </h2>

                    <p>
                        Escolha a especialidade que você precisa
                        e encontre profissionais disponíveis.
                    </p>

                </div>


                <div class="grid-especialidades">

                    <div class="especialidade">
                        ❤️
                        <strong>Cardiologia</strong>
                    </div>

                    <div class="especialidade">
                        👶
                        <strong>Pediatria</strong>
                    </div>

                    <div class="especialidade">
                        🦴
                        <strong>Ortopedia</strong>
                    </div>

                    <div class="especialidade">
                        🧠
                        <strong>Neurologia</strong>
                    </div>

                    <div class="especialidade">
                        👁️
                        <strong>Oftalmologia</strong>
                    </div>

                    <div class="especialidade">
                        🧴
                        <strong>Dermatologia</strong>
                    </div>

                    <div class="especialidade">
                        🩺
                        <strong>Clínica Geral</strong>
                    </div>

                    <div class="especialidade">
                        ➕
                        <strong>Ver todas</strong>
                    </div>

                </div>

            </div>

        </section>


        <!-- =====================================================
             UNIDADES
        ====================================================== -->

        <section class="unidades" id="unidades">

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
                        e encontre aquela que melhor atende às
                        suas necessidades.
                    </p>

                    <a href="paciente/agendar.php"
                       class="btn-principal">

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


        <!-- =====================================================
             COMO FUNCIONA
        ====================================================== -->

        <section class="como-funciona">

            <div class="container">

                <div class="titulo-secao">

                    <span>SIMPLES E RÁPIDO</span>

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


        <!-- =====================================================
             CHAMADA PARA AGENDAMENTO
        ====================================================== -->

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

                <a href="paciente/agendar.php"
                   class="btn-branco">

                    Agendar consulta

                </a>

            </div>

        </section>

    </main>


    <!-- =====================================================
         RODAPÉ
    ====================================================== -->

    <footer class="rodape">

        <div class="container rodape-conteudo">

            <div>

                <a href="index.php" class="logo rodape-logo">
                    <span>✚</span> AgendaSaúde
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

                <a href="#unidades">
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