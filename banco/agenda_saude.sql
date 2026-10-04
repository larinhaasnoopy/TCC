-- ============================================================================
-- BANCO DE DADOS COMPLETO E UNIFICADO - AGENDA_SAUDE
-- Sistema AgendaSaúde
-- Compatível com phpMyAdmin / XAMPP / MariaDB / MySQL
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `agenda_saude` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `agenda_saude`;

-- Desabilita temporariamente a checagem de chaves estrangeiras para permitir a criação/recriação completa
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `agendamento`;
DROP TABLE IF EXISTS `agenda`;
DROP TABLE IF EXISTS `unidade_servico`;
DROP TABLE IF EXISTS `servico_saude`;
DROP TABLE IF EXISTS `medico`;
DROP TABLE IF EXISTS `especialidade_medica`;
DROP TABLE IF EXISTS `unidade_saude`;
DROP TABLE IF EXISTS `usuario`;
DROP TABLE IF EXISTS `administrador`;

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- 1. Tabela: administrador
-- --------------------------------------------------------
CREATE TABLE `administrador` (
  `id_administrador` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_administrador`),
  UNIQUE KEY `uq_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `administrador` (`id_administrador`, `nome`, `email`, `senha`) VALUES
(1, 'Administrador Central', 'admin@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.'),
(2, 'Suporte Técnico', 'suporte@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.'),
(3, 'Gestão de Saúde Regional', 'gestao@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.');

-- --------------------------------------------------------
-- 2. Tabela: especialidade_medica
-- --------------------------------------------------------
CREATE TABLE `especialidade_medica` (
  `id_especialidade` INT(11) NOT NULL AUTO_INCREMENT,
  `nome_especialidade` VARCHAR(100) NOT NULL,
  `descricao` TEXT DEFAULT NULL,
  PRIMARY KEY (`id_especialidade`),
  UNIQUE KEY `uq_especialidade_nome` (`nome_especialidade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `especialidade_medica` (`id_especialidade`, `nome_especialidade`, `descricao`) VALUES
(1, 'Cardiologia', 'Diagnóstico e tratamento de doenças do coração e do sistema circulatório.'),
(2, 'Dermatologia', 'Atendimento especializado na saúde da pele, cabelos e unhas.'),
(3, 'Pediatria', 'Atendimento médico especializado na saúde infantil e de adolescentes.'),
(4, 'Ortopedia', 'Tratamento de lesões e doenças do sistema osteomuscular.'),
(5, 'Ginecologia e Obstetrícia', 'Cuidado integral da saúde da mulher e acompanhamento pré-natal.'),
(6, 'Neurologia', 'Diagnóstico e tratamento de doenças do sistema nervoso central e periférico.'),
(7, 'Oftalmologia', 'Cuidados preventivos, diagnósticos e tratamentos para a saúde ocular.'),
(8, 'Psiquiatria', 'Atendimento especializado em saúde mental e transtornos comportamentais.'),
(9, 'Endocrinologia', 'Tratamento de distúrbios hormonais, metabólicos e endocrinológicos.'),
(10, 'Clínica Geral', 'Atendimento médico primário, exames de rotina e avaliação geral da saúde.'),
(11, 'Otorrinolaringologia', 'Tratamento das doenças de ouvido, nariz, garganta e estruturas da face.'),
(12, 'Urologia', 'Tratamento do sistema urinário de ambos os sexos e sistema reprodutor masculino.'),
(13, 'Gastroenterologia', 'Diagnóstico e tratamento de doenças do aparelho digestivo.'),
(14, 'Pneumologia', 'Tratamento das doenças respiratórias, pulmões e vias aéreas.'),
(15, 'Oncologia', 'Diagnóstico e acompanhamento do tratamento de neoplasias e tumores.'),
(16, 'Reumatologia', 'Tratamento de doenças inflamatórias, autoimunes e reumatológicas.'),
(17, 'Nefrologia', 'Diagnóstico e tratamento de patologias dos rins e vias urinárias.'),
(18, 'Infectologia', 'Prevenção e tratamento de doenças infecciosas e parasitárias.'),
(19, 'Angiologia', 'Tratamento e prevenção de doenças dos vasos sanguíneos e linfáticos.'),
(20, 'Geriatria', 'Acompanhamento médico integral especializado na saúde da pessoa idosa.');

-- --------------------------------------------------------
-- 3. Tabela: medico
-- --------------------------------------------------------
CREATE TABLE `medico` (
  `id_medico` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `crm` VARCHAR(5) NOT NULL,
  `uf_crm` CHAR(2) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `id_especialidade` INT(11) NOT NULL,
  PRIMARY KEY (`id_medico`),
  UNIQUE KEY `uq_medico_crm_uf` (`crm`, `uf_crm`),
  UNIQUE KEY `uq_medico_email` (`email`),
  KEY `fk_medico_especialidade` (`id_especialidade`),
  CONSTRAINT `fk_medico_especialidade` FOREIGN KEY (`id_especialidade`) REFERENCES `especialidade_medica` (`id_especialidade`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `medico` (`id_medico`, `nome`, `crm`, `uf_crm`, `email`, `senha`, `id_especialidade`) VALUES
(1, 'Dr. Carlos Eduardo Silva', '10001', 'SP', 'carlos.silva@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 1),
(2, 'Dra. Mariana Santos Oliveira', '10002', 'SP', 'mariana.oliveira@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 1),
(3, 'Dr. Lucas Ferreira Costa', '10003', 'SP', 'lucas.costa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 1),
(4, 'Dra. Beatriz Lima Rodrigues', '10004', 'MG', 'beatriz.rodrigues@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 1),
(5, 'Dr. Guilherme Rocha Alves', '10005', 'SP', 'guilherme.alves@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 1),
(6, 'Dra. Fernanda Martins Souza', '10006', 'SP', 'fernanda.souza@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 1),
(7, 'Dr. Rafael Castro Barbosa', '10007', 'SP', 'rafael.barbosa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 1),
(8, 'Dra. Camila Ribeiro Mendes', '10008', 'MG', 'camila.mendes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 1),
(9, 'Dr. Gustavo Carvalho Fernandes', '10009', 'SP', 'gustavo.fernandes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 1),
(10, 'Dra. Patricia Gomes Azevedo', '10010', 'SP', 'patricia.azevedo@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 1),
(11, 'Dr. Thiago Nogueira Freitas', '10011', 'SP', 'thiago.freitas@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 2),
(12, 'Dra. Vanessa Carmo Prado', '10012', 'SP', 'vanessa.prado@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 2),
(13, 'Dr. Marcelo Mello Siqueira', '10013', 'MG', 'marcelo.siqueira@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 2),
(14, 'Dra. Renata Viana Xavier', '10014', 'SP', 'renata.xavier@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 2),
(15, 'Dr. Igor Zago Esteves', '10015', 'SP', 'igor.esteves@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 2),
(16, 'Dra. Amanda Aguiar Duarte', '10016', 'SP', 'amanda.duarte@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 2),
(17, 'Dr. Rodrigo Barros Faria', '10017', 'SP', 'rodrigo.faria@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 2),
(18, 'Dra. Larissa Lemos Henriques', '10018', 'MG', 'larissa.henriques@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 2),
(19, 'Dr. Diego Macedo Izidro', '10019', 'SP', 'diego.izidro@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 2),
(20, 'Dra. Bruna Nunes Jorge', '10020', 'SP', 'bruna.jorge@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 2),
(21, 'Dr. Andre Oliva Peixoto', '10021', 'SP', 'andre.peixoto@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 3),
(22, 'Dra. Juliana Quental Resende', '10022', 'SP', 'juliana.resende@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 3),
(23, 'Dr. Felipe Silveira Teixeira', '10023', 'MG', 'felipe.teixeira@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 3),
(24, 'Dra. Daniela Uchoa Valente', '10024', 'SP', 'daniela.valente@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 3),
(25, 'Dr. Bruno Wanderley Yunes', '10025', 'SP', 'bruno.yunes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 3),
(26, 'Dra. Gabriela Zabala Amorim', '10026', 'SP', 'gabriela.amorim@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 3),
(27, 'Dr. Vinicius Batista Chaves', '10027', 'SP', 'vinicius.chaves@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 3),
(28, 'Dra. Helena Domingues Evangelista', '10028', 'MG', 'helena.evangelista@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 3),
(29, 'Dr. Leonardo Fonseca Godoy', '10029', 'SP', 'leonardo.godoy@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 3),
(30, 'Dra. Isabela Hollanda Imperial', '10030', 'SP', 'isabela.imperial@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 3),
(31, 'Dr. Caio Jardim Klein', '10031', 'SP', 'caio.klein@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 4),
(32, 'Dra. Leticia Luz Miranda', '10032', 'SP', 'leticia.miranda@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 4),
(33, 'Dr. Matheus Neves Ortega', '10033', 'MG', 'matheus.ortega@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 4),
(34, 'Dra. Olivia Pacheco Queiroz', '10034', 'SP', 'olivia.queiroz@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 4),
(35, 'Dr. Pedro Ramos Soares', '10035', 'SP', 'pedro.soares@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 4),
(36, 'Dra. Sabrina Torres Urbano', '10036', 'SP', 'sabrina.urbano@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 4),
(37, 'Dr. Samuel Vasconcelos Washington', '10037', 'SP', 'samuel.washington@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 4),
(38, 'Dra. Tatiane Ximenes Yamashita', '10038', 'MG', 'tatiane.yamashita@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 4),
(39, 'Dr. Victor Zanetti Abreu', '10039', 'SP', 'victor.abreu@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 4),
(40, 'Dra. Yasmin Bueno Corrêa', '10040', 'SP', 'yasmin.correa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 4),
(41, 'Dr. Alan Diniz Enes', '10041', 'SP', 'alan.enes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 5),
(42, 'Dra. Barbara Figueira Guimaraes', '10042', 'SP', 'barbara.guimaraes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 5),
(43, 'Dr. Daniel Horta Iglezias', '10043', 'MG', 'daniel.iglezias@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 5),
(44, 'Dra. Eduarda Junqueira Kuster', '10044', 'SP', 'eduarda.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 5),
(45, 'Dr. Fernando Lacerda Mendonca', '10045', 'SP', 'fernando.mendonca@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 5),
(46, 'Dra. Giovanna Novaes Osterno', '10046', 'SP', 'giovanna.osterno@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 5),
(47, 'Dr. Henrique Paes Quadros', '10047', 'SP', 'henrique.quadros@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 5),
(48, 'Dra. Ines Rossi Sales', '10048', 'MG', 'ines.sales@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 5),
(49, 'Dr. Joao Tavora Uribe', '10049', 'SP', 'joao.uribe@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 5),
(50, 'Dra. Karen Vargas Wogel', '10050', 'SP', 'karen.wogel@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 5),
(51, 'Dr. Leandro Xavier Yunes', '10051', 'SP', 'leandro.yunes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 6),
(52, 'Dra. Maria Zabala Altamirano', '10052', 'SP', 'maria.altamirano@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 6),
(53, 'Dr. Nicolas Bicalho Capanema', '10053', 'MG', 'nicolas.capanema@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 6),
(54, 'Dra. Paola Delacroix Escalante', '10054', 'SP', 'paola.escalante@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 6),
(55, 'Dr. Ricardo Fragoso Galvao', '10055', 'SP', 'ricardo.galvao@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 6),
(56, 'Dra. Sofia Holanda Infantino', '10056', 'SP', 'sofia.infantino@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 6),
(57, 'Dr. Tomas Jacintho Kuster', '10057', 'SP', 'tomas.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 6),
(58, 'Dra. Valeria Lemgruber Maia', '10058', 'MG', 'valeria.maia@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 6),
(59, 'Dr. William Nogueira Oiticica', '10059', 'SP', 'william.oiticica@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 6),
(60, 'Dra. Zoey Palhares Quintas', '10060', 'SP', 'zoey.quintas@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 6),
(61, 'Dr. Arthur Rabelo Sampaios', '10061', 'SP', 'arthur.sampaios@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 7),
(62, 'Dra. Bianca Tufi Ulhoa', '10062', 'SP', 'bianca.ulhoa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 7),
(63, 'Dr. Cesar Vital Wanderley', '10063', 'MG', 'cesar.wanderley@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 7),
(64, 'Dra. Debora Xavier Ypiranga', '10064', 'SP', 'debora.ypiranga@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 7),
(65, 'Dr. Erick Zucchi Abrantes', '10065', 'SP', 'erick.abrantes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 7),
(66, 'Dra. Flavia Beltrão Capanema', '10066', 'SP', 'flavia.capanema@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 7),
(67, 'Dr. Gabriel Delacroix Escalante', '10067', 'SP', 'gabriel.escalante@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 7),
(68, 'Dra. Helen Fragoso Galvao', '10068', 'MG', 'helen.galvao@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 7),
(69, 'Dr. Ian Holanda Infantino', '10069', 'SP', 'ian.infantino@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 7),
(70, 'Dra. Jessica Jacintho Kuster', '10070', 'SP', 'jessica.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 7),
(71, 'Dr. Kleber Lemgruber Maia', '10071', 'SP', 'kleber.maia@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 8),
(72, 'Dra. Luana Nogueira Oiticica', '10072', 'SP', 'luana.oiticica@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 8),
(73, 'Dr. Murilo Palhares Quintas', '10073', 'MG', 'murilo.quintas@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 8),
(74, 'Dra. Nicole Rabelo Sampaios', '10074', 'SP', 'nicole.sampaios@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 8),
(75, 'Dr. Otavio Tufi Ulhoa', '10075', 'SP', 'otavio.ulhoa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 8),
(76, 'Dra. Paloma Vital Wanderley', '10076', 'SP', 'paloma.wanderley@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 8),
(77, 'Dr. Quirino Xavier Ypiranga', '10077', 'SP', 'quirino.ypiranga@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 8),
(78, 'Dra. Raquel Zucchi Abrantes', '10078', 'MG', 'raquel.abrantes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 8),
(79, 'Dr. Sergio Beltrão Capanema', '10079', 'SP', 'sergio.capanema@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 8),
(80, 'Dra. Teresa Delacroix Escalante', '10080', 'SP', 'teresa.escalante@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 8),
(81, 'Dr. Uriel Fragoso Galvao', '10081', 'SP', 'uriel.galvao@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 9),
(82, 'Dra. Vera Holanda Infantino', '10082', 'SP', 'vera.infantino@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 9),
(83, 'Dr. Wagner Jacintho Kuster', '10083', 'MG', 'wagner.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 9),
(84, 'Dra. Xiomara Lemgruber Maia', '10084', 'SP', 'xiomara.maia@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 9),
(85, 'Dr. Yuri Nogueira Oiticica', '10085', 'SP', 'yuri.oiticica@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 9),
(86, 'Dra. Zilda Palhares Quintas', '10086', 'SP', 'zilda.quintas@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 9),
(87, 'Dr. Adriano Rabelo Sampaios', '10087', 'SP', 'adriano.sampaios@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 9),
(88, 'Dra. Brenda Tufi Ulhoa', '10088', 'MG', 'brenda.ulhoa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 9),
(89, 'Dr. Claudio Vital Wanderley', '10089', 'SP', 'claudio.wanderley@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 9),
(90, 'Dra. Diana Xavier Ypiranga', '10090', 'SP', 'diana.ypiranga@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 9),
(91, 'Dr. Emilio Zucchi Abrantes', '10091', 'SP', 'emilio.abrantes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 10),
(92, 'Dra. Fabiana Beltrão Capanema', '10092', 'SP', 'fabiana.capanema@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 10),
(93, 'Dr. Geraldo Delacroix Escalante', '10093', 'MG', 'geraldo.escalante@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 10),
(94, 'Dra. Heloisa Fragoso Galvao', '10094', 'SP', 'heloisa.galvao@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 10),
(95, 'Dr. Italo Holanda Infantino', '10095', 'SP', 'italo.infantino@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 10),
(96, 'Dra. Joana Jacintho Kuster', '10096', 'SP', 'joana.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 10),
(97, 'Dr. Lauro Lemgruber Maia', '10097', 'SP', 'lauro.maia@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 10),
(98, 'Dra. Milena Nogueira Oiticica', '10098', 'MG', 'milena.oiticica@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 10),
(99, 'Dr. Norbert Palhares Quintas', '10099', 'SP', 'norbert.quintas@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 10),
(100, 'Dra. Olga Rabelo Sampaios', '10100', 'SP', 'olga.sampaios@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 10),
(101, 'Dr. Paulo Tufi Ulhoa', '10101', 'SP', 'paulo.ulhoa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 11),
(102, 'Dra. Quinta Vital Wanderley', '10102', 'SP', 'quinta.wanderley@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 11),
(103, 'Dr. Roberto Xavier Ypiranga', '10103', 'MG', 'roberto.ypiranga@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 11),
(104, 'Dra. Silvia Zucchi Abrantes', '10104', 'SP', 'silvia.abrantes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 11),
(105, 'Dr. Tarcisio Beltrão Capanema', '10105', 'SP', 'tarcisio.capanema@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 11),
(106, 'Dra. Ursula Delacroix Escalante', '10106', 'SP', 'ursula.escalante@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 11),
(107, 'Dr. Vicente Fragoso Galvao', '10107', 'SP', 'vicente.galvao@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 11),
(108, 'Dra. Wanda Holanda Infantino', '10108', 'MG', 'wanda.infantino@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 11),
(109, 'Dr. Ximenes Jacintho Kuster', '10109', 'SP', 'ximenes.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 11),
(110, 'Dra. Yara Lemgruber Maia', '10110', 'SP', 'yara.maia@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 11),
(111, 'Dr. Zaqueu Nogueira Oiticica', '10111', 'SP', 'zaqueu.oiticica@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 12),
(112, 'Dra. Alice Palhares Quintas', '10112', 'SP', 'alice.quintas@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 12),
(113, 'Dr. Bernardo Rabelo Sampaios', '10113', 'MG', 'bernardo.sampaios@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 12),
(114, 'Dra. Cristina Tufi Ulhoa', '10114', 'SP', 'cristina.ulhoa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 12),
(115, 'Dr. Douglas Vital Wanderley', '10115', 'SP', 'douglas.wanderley@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 12),
(116, 'Dra. Eliana Xavier Ypiranga', '10116', 'SP', 'eliana.ypiranga@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 12),
(117, 'Dr. Fabricio Zucchi Abrantes', '10117', 'SP', 'fabricio.abrantes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 12),
(118, 'Dra. Giselle Beltrão Capanema', '10118', 'MG', 'giselle.capanema@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 12),
(119, 'Dr. Hélio Delacroix Escalante', '10119', 'SP', 'helio.escalante@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 12),
(120, 'Dra. Iris Fragoso Galvao', '10120', 'SP', 'iris.galvao@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 12),
(121, 'Dr. Joaquim Holanda Infantino', '10121', 'SP', 'joaquim.infantino@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 13),
(122, 'Dra. Kelly Jacintho Kuster', '10122', 'SP', 'kelly.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 13),
(123, 'Dr. Luis Felipe Lemgruber Maia', '10123', 'MG', 'luis.maia@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 13),
(124, 'Dra. Monica Nogueira Oiticica', '10124', 'SP', 'monica.oiticica@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 13),
(125, 'Dr. Nelson Palhares Quintas', '10125', 'SP', 'nelson.quintas@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 13),
(126, 'Dra. Ophelia Rabelo Sampaios', '10126', 'SP', 'ophelia.sampaios@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 13),
(127, 'Dr. Pablo Tufi Ulhoa', '10127', 'SP', 'pablo.ulhoa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 13),
(128, 'Dra. Queenie Vital Wanderley', '10128', 'MG', 'queenie.wanderley@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 13),
(129, 'Dr. Ramon Xavier Ypiranga', '10129', 'SP', 'ramon.ypiranga@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 13),
(130, 'Dra. Simone Zucchi Abrantes', '10130', 'SP', 'simone.abrantes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 13),
(131, 'Dr. Tristan Beltrão Capanema', '10131', 'SP', 'tristan.capanema@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 14),
(132, 'Dra. Ully Delacroix Escalante', '10132', 'SP', 'ully.escalante@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 14),
(133, 'Dr. Vladimir Fragoso Galvao', '10133', 'MG', 'vladimir.galvao@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 14),
(134, 'Dra. Wendy Holanda Infantino', '10134', 'SP', 'wendy.infantino@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 14),
(135, 'Dr. Xavier Jacintho Kuster', '10135', 'SP', 'xavier.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 14),
(136, 'Dra. Yasmine Lemgruber Maia', '10136', 'SP', 'yasmine.maia@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 14),
(137, 'Dr. Zander Nogueira Oiticica', '10137', 'SP', 'zander.oiticica@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 14),
(138, 'Dra. Abigail Palhares Quintas', '10138', 'MG', 'abigail.quintas@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 14),
(139, 'Dr. Benjamin Rabelo Sampaios', '10139', 'SP', 'benjamin.sampaios@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 14),
(140, 'Dra. Cecilia Tufi Ulhoa', '10140', 'SP', 'cecilia.ulhoa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 14),
(141, 'Dr. David Vital Wanderley', '10141', 'SP', 'david.wanderley@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 15),
(142, 'Dra. Elisa Xavier Ypiranga', '10142', 'SP', 'elisa.ypiranga@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 15),
(143, 'Dr. Francisco Zucchi Abrantes', '10143', 'MG', 'francisco.abrantes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 15),
(144, 'Dra. Gloria Beltrão Capanema', '10144', 'SP', 'gloria.capanema@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 15),
(145, 'Dr. Hugo Delacroix Escalante', '10145', 'SP', 'hugo.escalante@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 15),
(146, 'Dra. Inez Fragoso Galvao', '10146', 'SP', 'inez.galvao@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 15),
(147, 'Dr. Jorge Holanda Infantino', '10147', 'SP', 'jorge.infantino@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 15),
(148, 'Dra. Karen Jacintho Kuster', '10148', 'MG', 'karen.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 15),
(149, 'Dr. Leo Lemgruber Maia', '10149', 'SP', 'leo.maia@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 15),
(150, 'Dra. Maya Nogueira Oiticica', '10150', 'SP', 'maya.oiticica@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 15),
(151, 'Dr. Nathan Palhares Quintas', '10151', 'SP', 'nathan.quintas@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 16),
(152, 'Dra. Olivia Rabelo Sampaios', '10152', 'SP', 'olivia.sampaios2@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 16),
(153, 'Dr. Patrick Tufi Ulhoa', '10153', 'MG', 'patrick.ulhoa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 16),
(154, 'Dra. Quenia Vital Wanderley', '10154', 'SP', 'quenia.wanderley@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 16),
(155, 'Dr. Renato Xavier Ypiranga', '10155', 'SP', 'renato.ypiranga@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 16),
(156, 'Dra. Stella Zucchi Abrantes', '10156', 'SP', 'stella.abrantes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 16),
(157, 'Dr. Tulio Beltrão Capanema', '10157', 'SP', 'tulio.capanema@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 16),
(158, 'Dra. Ureice Delacroix Escalante', '10158', 'MG', 'ureice.escalante@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 16),
(159, 'Dr. Valter Fragoso Galvao', '10159', 'SP', 'valter.galvao@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 16),
(160, 'Dra. Waleria Holanda Infantino', '10160', 'SP', 'waleria.infantino@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 16),
(161, 'Dr. Yago Jacintho Kuster', '10161', 'SP', 'yago.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 17),
(162, 'Dra. Zulmira Lemgruber Maia', '10162', 'SP', 'zulmira.maia@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 17),
(163, 'Dr. Alvaro Nogueira Oiticica', '10163', 'MG', 'alvaro.oiticica@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 17),
(164, 'Dra. Beatrice Palhares Quintas', '10164', 'SP', 'beatrice.quintas@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 17),
(165, 'Dr. Cristian Rabelo Sampaios', '10165', 'SP', 'cristian.sampaios@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 17),
(166, 'Dra. Dora Tufi Ulhoa', '10166', 'SP', 'dora.ulhoa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 17),
(167, 'Dr. Enzo Vital Wanderley', '10167', 'SP', 'enzo.wanderley@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 17),
(168, 'Dra. Fatima Xavier Ypiranga', '10168', 'MG', 'fatima.ypiranga@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 17),
(169, 'Dr. Gilberto Zucchi Abrantes', '10169', 'SP', 'gilberto.abrantes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 17),
(170, 'Dra. Hilda Beltrão Capanema', '10170', 'SP', 'hilda.capanema@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 17),
(171, 'Dr. Ivan Delacroix Escalante', '10171', 'SP', 'ivan.escalante@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 18),
(172, 'Dra. Joyce Fragoso Galvao', '10172', 'SP', 'joyce.galvao@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 18),
(173, 'Dr. Kevin Holanda Infantino', '10173', 'MG', 'kevin.infantino@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 18),
(174, 'Dra. Luisa Jacintho Kuster', '10174', 'SP', 'luisa.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 18),
(175, 'Dr. Mario Lemgruber Maia', '10175', 'SP', 'mario.maia@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 18),
(176, 'Dra. Nina Nogueira Oiticica', '10176', 'SP', 'nina.oiticica@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 18),
(177, 'Dr. Orlando Palhares Quintas', '10177', 'SP', 'orlando.quintas@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 18),
(178, 'Dra. Priscilla Rabelo Sampaios', '10178', 'MG', 'priscilla.sampaios@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 18),
(179, 'Dr. Quintino Tufi Ulhoa', '10179', 'SP', 'quintino.ulhoa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 18),
(180, 'Dra. Rita Vital Wanderley', '10180', 'SP', 'rita.wanderley@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 18),
(181, 'Dr. Silvio Xavier Ypiranga', '10181', 'SP', 'silvio.ypiranga@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 19),
(182, 'Dra. Tania Zucchi Abrantes', '10182', 'SP', 'tania.abrantes@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 19),
(183, 'Dr. Ulisses Beltrão Capanema', '10183', 'MG', 'ulisses.capanema@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 19),
(184, 'Dra. Vera Delacroix Escalante', '10184', 'SP', 'vera.escalante2@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 19),
(185, 'Dr. Wagner Fragoso Galvao', '10185', 'SP', 'wagner.galvao2@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 19),
(186, 'Dra. Xenia Holanda Infantino', '10186', 'SP', 'xenia.infantino@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 19),
(187, 'Dr. Yasser Jacintho Kuster', '10187', 'SP', 'yasser.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 19),
(188, 'Dra. Zuleica Lemgruber Maia', '10188', 'MG', 'zuleica.maia@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 19),
(189, 'Dr. Aldo Nogueira Oiticica', '10189', 'SP', 'aldo.oiticica@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 19),
(190, 'Dra. Bruna Palhares Quintas', '10190', 'SP', 'bruna.quintas@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 19),
(191, 'Dr. Celso Rabelo Sampaios', '10191', 'SP', 'celso.sampaios@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 20),
(192, 'Dra. Denise Tufi Ulhoa', '10192', 'SP', 'denise.ulhoa@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 20),
(193, 'Dr. Edgar Vital Wanderley', '10193', 'MG', 'edgar.wanderley@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 20),
(194, 'Dra. Fatima Xavier Ypiranga', '10194', 'SP', 'fatima.ypiranga2@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 20),
(195, 'Dr. Geraldo Zucchi Abrantes', '10195', 'SP', 'geraldo.abrantes2@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 20),
(196, 'Dra. Heloisa Beltrão Capanema', '10196', 'SP', 'heloisa.capanema@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 20),
(197, 'Dr. Ivan Delacroix Escalante', '10197', 'SP', 'ivan.escalante2@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 20),
(198, 'Dra. Julia Fragoso Galvao', '10198', 'MG', 'julia.galvao@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 20),
(199, 'Dr. Kleber Holanda Infantino', '10199', 'SP', 'kleber.infantino@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 20),
(200, 'Dra. Lucia Jacintho Kuster', '10200', 'SP', 'lucia.kuster@agendasaude.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', 20);

-- --------------------------------------------------------
-- 4. Tabela: servico_saude
-- --------------------------------------------------------
CREATE TABLE `servico_saude` (
  `id_servico` INT(11) NOT NULL AUTO_INCREMENT,
  `tipo_atendimento` VARCHAR(100) NOT NULL,
  `descricao` TEXT DEFAULT NULL,
  `duracao` TIME NOT NULL,
  `id_especialidade` INT(11) NOT NULL,
  PRIMARY KEY (`id_servico`),
  KEY `fk_servico_especialidade` (`id_especialidade`),
  CONSTRAINT `fk_servico_especialidade` FOREIGN KEY (`id_especialidade`) REFERENCES `especialidade_medica` (`id_especialidade`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `servico_saude` (`id_servico`, `tipo_atendimento`, `descricao`, `duracao`, `id_especialidade`) VALUES
(1, 'Consulta', 'Atendimento primário e avaliação diagnóstica especializada em Cardiologia.', '00:30:00', 1),
(2, 'Retorno', 'Retorno médico para reavaliação clínica e apresentação de exames cardiológicos.', '00:20:00', 1),
(3, 'Consulta', 'Atendimento primário e avaliação diagnóstica especializada em Dermatologia.', '00:30:00', 2),
(4, 'Retorno', 'Retorno médico para reavaliação clínica e acompanhamento dermatológico.', '00:20:00', 2),
(5, 'Consulta', 'Atendimento primário e acompanhamento pediátrico integral.', '00:30:00', 3),
(6, 'Retorno', 'Retorno médico pediátrico para acompanhamento do tratamento.', '00:20:00', 3),
(7, 'Consulta', 'Atendimento primário e avaliação ortopédica ortomolecular.', '00:30:00', 4),
(8, 'Retorno', 'Retorno médico ortopédico para reavaliação de exames e conduta.', '00:20:00', 4),
(9, 'Consulta', 'Atendimento primário ginecológico e preventivo.', '00:30:00', 5),
(10, 'Retorno', 'Retorno médico ginecológico para acompanhamento de exames.', '00:20:00', 5),
(11, 'Consulta', 'Atendimento primário e avaliação diagnóstica em Neurologia.', '00:30:00', 6),
(12, 'Retorno', 'Retorno médico neurológico para acompanhamento evolutivo.', '00:20:00', 6),
(13, 'Consulta', 'Atendimento primário e exames de refração oftalmológica.', '00:30:00', 7),
(14, 'Retorno', 'Retorno médico oftalmológico para verificação e adaptação.', '00:20:00', 7),
(15, 'Consulta', 'Atendimento primário e avaliação psiquiátrica especializada.', '00:45:00', 8),
(16, 'Retorno', 'Retorno médico psiquiátrico para reavaliação medicamentosa.', '00:30:00', 8),
(17, 'Consulta', 'Atendimento primário e avaliação hormonal endocrinológica.', '00:30:00', 9),
(18, 'Retorno', 'Retorno médico endocrinológico para ajuste terapêutico.', '00:20:00', 9),
(19, 'Consulta', 'Atendimento médico geral e check-up de rotina.', '00:30:00', 10),
(20, 'Retorno', 'Retorno de clínica geral para avaliação de exames laboratoriais.', '00:20:00', 10),
(21, 'Consulta', 'Atendimento otorrinolaringológico especializado.', '00:30:00', 11),
(22, 'Retorno', 'Retorno otorrinolaringológico para reavaliação clínica.', '00:20:00', 11),
(23, 'Consulta', 'Atendimento urológico especializado.', '00:30:00', 12),
(24, 'Retorno', 'Retorno urológico para acompanhamento de tratamento.', '00:20:00', 12),
(25, 'Consulta', 'Atendimento gastroenterológico especializado.', '00:30:00', 13),
(26, 'Retorno', 'Retorno gastroenterológico para reavaliação.', '00:20:00', 13),
(27, 'Consulta', 'Atendimento pneumológico especializado.', '00:30:00', 14),
(28, 'Retorno', 'Retorno pneumológico para verificação de exames funcionais.', '00:20:00', 14),
(29, 'Consulta', 'Atendimento oncológico especializado.', '00:45:00', 15),
(30, 'Retorno', 'Retorno oncológico para acompanhamento condutivo.', '00:30:00', 15),
(31, 'Consulta', 'Atendimento reumatológico especializado.', '00:30:00', 16),
(32, 'Retorno', 'Retorno reumatológico para acompanhamento clínico.', '00:20:00', 16),
(33, 'Consulta', 'Atendimento nefrológico especializado.', '00:30:00', 17),
(34, 'Retorno', 'Retorno nefrológico para avaliação de função renal.', '00:20:00', 17),
(35, 'Consulta', 'Atendimento infectológico especializado.', '00:30:00', 18),
(36, 'Retorno', 'Retorno infectológico para acompanhamento.', '00:20:00', 18),
(37, 'Consulta', 'Atendimento angiológico e vascular especializado.', '00:30:00', 19),
(38, 'Retorno', 'Retorno angiológico para reavaliação vascular.', '00:20:00', 19),
(39, 'Consulta', 'Atendimento geriátrico integral especializado.', '00:45:00', 20),
(40, 'Retorno', 'Retorno geriátrico para acompanhamento de condutas.', '00:30:00', 20);

-- --------------------------------------------------------
-- 5. Tabela: unidade_saude
-- --------------------------------------------------------
CREATE TABLE `unidade_saude` (
  `id_unidade` INT(11) NOT NULL AUTO_INCREMENT,
  `nome_unidade` VARCHAR(150) NOT NULL,
  `endereco` VARCHAR(255) NOT NULL,
  `localizacao` VARCHAR(100) NOT NULL,
  `telefone` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`id_unidade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `unidade_saude` (`id_unidade`, `nome_unidade`, `endereco`, `localizacao`, `telefone`) VALUES
(1, 'UBS Central Dra. Maria Inês - São Joaquim da Barra', 'Rua São Paulo, 450 - Centro', 'São Joaquim da Barra/SP', '(16) 3818-1100'),
(2, 'Pronto Atendimento Municipal - São Joaquim da Barra', 'Av. Dr. Eduardo de Oliveira, 1200 - Jardim Amália', 'São Joaquim da Barra/SP', '(16) 3818-2200'),
(3, 'Centro Especializado de Saúde - São Joaquim da Barra', 'Rua Paraná, 880 - Centro', 'São Joaquim da Barra/SP', '(16) 3818-3300'),
(4, 'AME Franca - Ambulatório Médico de Especialidades', 'Av. Dr. Flávio Rocha, 4780 - Vila Imperador', 'Franca/SP', '(16) 3712-3000'),
(5, 'Hospital das Clínicas de Franca', 'Rua Passo Fundo, 255 - Jardim Petráglia', 'Franca/SP', '(16) 3711-4000'),
(6, 'UBS Estação - Franca', 'Rua Frei Germano, 2010 - Estação', 'Franca/SP', '(16) 3712-5500'),
(7, 'UBS Centro - Orlândia', 'Rua 6, 890 - Centro', 'Orlândia/SP', '(16) 3820-8000'),
(8, 'Pronto Socorro Municipal - Orlândia', 'Av. 2, 500 - Jardim Rosa', 'Orlândia/SP', '(16) 3820-8100'),
(9, 'Hospital Escola de Uberaba', 'Av. Getúlio Guaritá, 130 - Abadia', 'Uberaba/MG', '(34) 3318-5000'),
(10, 'UBS Abadia - Uberaba', 'Rua Saldanha Marinho, 400 - Abadia', 'Uberaba/MG', '(34) 3318-5100'),
(11, 'Centro Médico de Especialidades - Uberaba', 'Av. Leopoldino de Oliveira, 2800 - Centro', 'Uberaba/MG', '(34) 3318-9000'),
(12, 'Hospital Santa Lydia - Ribeirão Preto', 'Rua Teresina, 678 - Sumarezinho', 'Ribeirão Preto/SP', '(16) 3605-1000'),
(13, 'HC Criança - Ribeirão Preto', 'Av. Bandeirantes, 3900 - Vila Monte Alegre', 'Ribeirão Preto/SP', '(16) 3602-1000'),
(14, 'UBS Central - Ribeirão Preto', 'Rua Amador Bueno, 333 - Centro', 'Ribeirão Preto/SP', '(16) 3605-2000'),
(15, 'AME Ribeirão Preto', 'Rua Capitão Salomão, 1200 - Campos Elíseos', 'Ribeirão Preto/SP', '(16) 3603-7000'),
(16, 'Hospital Municipal - Ituverava', 'Rua Capitão Flávio, 510 - Centro', 'Ituverava/SP', '(16) 3830-1200'),
(17, 'UBS Central - Ituverava', 'Av. Dr. Soares de Oliveira, 1100 - Centro', 'Ituverava/SP', '(16) 3830-1500'),
(18, 'Pronto Socorro - Ituverava', 'Rua Coronel Krause, 340 - Estação', 'Ituverava/SP', '(16) 3830-1900'),
(19, 'UBS Vila Nova - São Joaquim da Barra', 'Rua Alagoas, 1020 - Vila Nova', 'São Joaquim da Barra/SP', '(16) 3818-4400'),
(20, 'UBS Cidade Nova - Franca', 'Av. Orlando Dompieri, 1500 - Cidade Nova', 'Franca/SP', '(16) 3712-8800');

-- --------------------------------------------------------
-- 6. Tabela: unidade_servico
-- --------------------------------------------------------
CREATE TABLE `unidade_servico` (
  `id_unidade` INT(11) NOT NULL,
  `id_servico` INT(11) NOT NULL,
  PRIMARY KEY (`id_unidade`, `id_servico`),
  KEY `fk_us_servico` (`id_servico`),
  CONSTRAINT `fk_us_servico` FOREIGN KEY (`id_servico`) REFERENCES `servico_saude` (`id_servico`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_us_unidade` FOREIGN KEY (`id_unidade`) REFERENCES `unidade_saude` (`id_unidade`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `unidade_servico` (`id_unidade`, `id_servico`)
SELECT u.id_unidade, s.id_servico
FROM `unidade_saude` u
CROSS JOIN `servico_saude` s;

-- --------------------------------------------------------
-- 7. Tabela: usuario
-- --------------------------------------------------------
CREATE TABLE `usuario` (
  `id_usuario` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `cpf` VARCHAR(11) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `telefone` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `uq_usuario_cpf` (`cpf`),
  UNIQUE KEY `uq_usuario_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuario` (`id_usuario`, `nome`, `cpf`, `email`, `senha`, `telefone`) VALUES
(1, 'João da Silva Santos', '11122233301', 'joao.silva@paciente.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', '(16) 99111-0001'),
(2, 'Maria Oliveira Rocha', '22233344402', 'maria.oliveira@paciente.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', '(16) 99222-0002'),
(3, 'Pedro Henrique Costa', '33344455503', 'pedro.costa@paciente.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', '(16) 99333-0003'),
(4, 'Ana Beatriz Souza', '44455566604', 'ana.souza@paciente.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', '(16) 99444-0004'),
(5, 'Lucas Gabriel Pereira', '55566677705', 'lucas.pereira@paciente.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', '(16) 99555-0005'),
(6, 'Juliana Martins Ferreira', '66677788806', 'juliana.ferreira@paciente.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', '(16) 99666-0006'),
(7, 'Gabriel Rodrigues Alves', '77788899907', 'gabriel.alves@paciente.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', '(16) 99777-0007'),
(8, 'Fernanda Lima Barbosa', '88899900008', 'fernanda.barbosa@paciente.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', '(16) 99888-0008'),
(9, 'Roberto Carlos Mendes', '99900011109', 'roberto.mendes@paciente.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', '(16) 99999-0009'),
(10, 'Camila Duarte Ramos', '00011122210', 'camila.ramos@paciente.com', '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.', '(16) 99000-0010');

-- --------------------------------------------------------
-- 8. Tabela: agenda
-- --------------------------------------------------------
CREATE TABLE `agenda` (
  `id_agenda` INT(11) NOT NULL AUTO_INCREMENT,
  `data` DATE NOT NULL,
  `horario_disponivel` TIME NOT NULL,
  `status_horario` ENUM('disponivel','reservado','cancelado') NOT NULL DEFAULT 'disponivel',
  `id_medico` INT(11) NOT NULL,
  `id_servico` INT(11) NOT NULL,
  `id_unidade` INT(11) NOT NULL,
  PRIMARY KEY (`id_agenda`),
  UNIQUE KEY `uq_agenda_medico_data_horario` (`id_medico`, `data`, `horario_disponivel`),
  KEY `fk_agenda_servico` (`id_servico`),
  KEY `fk_agenda_unidade` (`id_unidade`),
  CONSTRAINT `fk_agenda_medico` FOREIGN KEY (`id_medico`) REFERENCES `medico` (`id_medico`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_agenda_servico` FOREIGN KEY (`id_servico`) REFERENCES `servico_saude` (`id_servico`) ON UPDATE CASCADE,
  CONSTRAINT `fk_agenda_unidade` FOREIGN KEY (`id_unidade`) REFERENCES `unidade_saude` (`id_unidade`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `agenda` (`id_agenda`, `data`, `horario_disponivel`, `status_horario`, `id_medico`, `id_servico`, `id_unidade`) VALUES
-- Médico 1 (Cardiologia - Unidade 1)
(1, '2026-10-01', '08:00:00', 'disponivel', 1, 1, 1),
(2, '2026-10-01', '08:30:00', 'reservado', 1, 1, 1),
(3, '2026-10-01', '09:00:00', 'disponivel', 1, 1, 1),
(4, '2026-10-01', '09:30:00', 'disponivel', 1, 1, 1),
(5, '2026-10-01', '10:00:00', 'disponivel', 1, 1, 1),
(6, '2026-10-01', '10:30:00', 'disponivel', 1, 1, 1),
(7, '2026-10-01', '14:00:00', 'disponivel', 1, 2, 1),
(8, '2026-10-01', '14:30:00', 'disponivel', 1, 2, 1),
(9, '2026-10-01', '15:00:00', 'disponivel', 1, 2, 1),

(10, '2026-10-02', '08:00:00', 'reservado', 1, 1, 1),
(11, '2026-10-02', '08:30:00', 'disponivel', 1, 1, 1),
(12, '2026-10-02', '09:00:00', 'disponivel', 1, 1, 1),
(13, '2026-10-02', '09:30:00', 'disponivel', 1, 1, 1),
(14, '2026-10-02', '14:00:00', 'disponivel', 1, 2, 1),
(15, '2026-10-02', '14:30:00', 'disponivel', 1, 2, 1),

(16, '2026-10-05', '08:00:00', 'disponivel', 1, 1, 1),
(17, '2026-10-05', '08:30:00', 'disponivel', 1, 1, 1),
(18, '2026-10-05', '09:00:00', 'reservado', 1, 1, 1),
(19, '2026-10-05', '09:30:00', 'disponivel', 1, 1, 1),
(20, '2026-10-05', '14:00:00', 'disponivel', 1, 2, 1),

-- Médico 2 (Cardiologia - Unidade 4)
(21, '2026-10-01', '08:00:00', 'disponivel', 2, 1, 4),
(22, '2026-10-01', '08:30:00', 'disponivel', 2, 1, 4),
(23, '2026-10-01', '09:00:00', 'disponivel', 2, 1, 4),
(24, '2026-10-01', '09:30:00', 'reservado', 2, 1, 4),
(25, '2026-10-01', '10:00:00', 'disponivel', 2, 1, 4),
(26, '2026-10-01', '14:00:00', 'disponivel', 2, 2, 4),
(27, '2026-10-01', '14:30:00', 'disponivel', 2, 2, 4),

(28, '2026-10-02', '08:00:00', 'disponivel', 2, 1, 4),
(29, '2026-10-02', '08:30:00', 'disponivel', 2, 1, 4),
(30, '2026-10-02', '09:00:00', 'disponivel', 2, 1, 4),
(31, '2026-10-02', '14:00:00', 'reservado', 2, 2, 4),

-- Médico 11 (Dermatologia - Unidade 2)
(32, '2026-10-01', '08:00:00', 'disponivel', 11, 3, 2),
(33, '2026-10-01', '08:30:00', 'disponivel', 11, 3, 2),
(34, '2026-10-01', '09:00:00', 'disponivel', 11, 3, 2),
(35, '2026-10-01', '09:30:00', 'disponivel', 11, 3, 2),
(36, '2026-10-01', '14:00:00', 'reservado', 11, 4, 2),
(37, '2026-10-01', '14:30:00', 'disponivel', 11, 4, 2),

(38, '2026-10-02', '08:00:00', 'disponivel', 11, 3, 2),
(39, '2026-10-02', '08:30:00', 'disponivel', 11, 3, 2),
(40, '2026-10-02', '09:00:00', 'disponivel', 11, 3, 2),

-- Médico 21 (Pediatria - Unidade 7)
(41, '2026-10-01', '08:00:00', 'disponivel', 21, 5, 7),
(42, '2026-10-01', '08:30:00', 'disponivel', 21, 5, 7),
(43, '2026-10-01', '09:00:00', 'disponivel', 21, 5, 7),
(44, '2026-10-01', '09:30:00', 'disponivel', 21, 5, 7),
(45, '2026-10-01', '14:00:00', 'disponivel', 21, 6, 7),

(46, '2026-10-02', '08:00:00', 'reservado', 21, 5, 7),
(47, '2026-10-02', '08:30:00', 'disponivel', 21, 5, 7),
(48, '2026-10-02', '09:00:00', 'disponivel', 21, 5, 7),

-- Médico 31 (Ortopedia - Unidade 9)
(49, '2026-10-01', '08:00:00', 'disponivel', 31, 7, 9),
(50, '2026-10-01', '08:30:00', 'disponivel', 31, 7, 9),
(51, '2026-10-01', '09:00:00', 'disponivel', 31, 7, 9),
(52, '2026-10-01', '14:00:00', 'disponivel', 31, 8, 9),

(53, '2026-10-05', '08:00:00', 'disponivel', 31, 7, 9),
(54, '2026-10-05', '08:30:00', 'disponivel', 31, 7, 9),

-- Médico 41 (Ginecologia - Unidade 12)
(55, '2026-10-01', '08:00:00', 'disponivel', 41, 9, 12),
(56, '2026-10-01', '08:30:00', 'disponivel', 41, 9, 12),
(57, '2026-10-01', '09:00:00', 'disponivel', 41, 9, 12),
(58, '2026-10-01', '14:00:00', 'reservado', 41, 10, 12),

-- Médico 51 (Neurologia - Unidade 14)
(59, '2026-10-01', '08:00:00', 'disponivel', 51, 11, 14),
(60, '2026-10-01', '08:30:00', 'disponivel', 51, 11, 14),
(61, '2026-10-01', '09:00:00', 'disponivel', 51, 11, 14),

-- Médico 91 (Clínica Geral - Unidade 16)
(62, '2026-10-01', '08:00:00', 'disponivel', 91, 19, 16),
(63, '2026-10-01', '08:30:00', 'disponivel', 91, 19, 16),
(64, '2026-10-01', '09:00:00', 'disponivel', 91, 19, 16),
(65, '2026-10-01', '09:30:00', 'disponivel', 91, 19, 16),
(66, '2026-10-01', '10:00:00', 'reservado', 91, 19, 16),
(67, '2026-10-01', '14:00:00', 'disponivel', 91, 20, 16),

-- Datas futuras em Novembro / 2026 para consultas de longo prazo
(68, '2026-11-03', '08:00:00', 'disponivel', 1, 1, 1),
(69, '2026-11-03', '08:30:00', 'disponivel', 1, 1, 1),
(70, '2026-11-03', '09:00:00', 'disponivel', 1, 1, 1),
(71, '2026-11-03', '08:00:00', 'disponivel', 11, 3, 2),
(72, '2026-11-03', '08:30:00', 'disponivel', 11, 3, 2),
(73, '2026-11-04', '08:00:00', 'disponivel', 21, 5, 7),
(74, '2026-11-04', '08:30:00', 'disponivel', 21, 5, 7),
(75, '2026-11-05', '08:00:00', 'disponivel', 91, 19, 16);

-- --------------------------------------------------------
-- 9. Tabela: agendamento
-- --------------------------------------------------------
CREATE TABLE `agendamento` (
  `id_agendamento` INT(11) NOT NULL AUTO_INCREMENT,
  `data_agendamento` DATE NOT NULL,
  `horario` TIME NOT NULL,
  `status` ENUM('pendente','confirmado','cancelado','em_atendimento','concluido') NOT NULL DEFAULT 'pendente',
  `lembrete_ativo` TINYINT(1) NOT NULL DEFAULT 0,
  `id_usuario` INT(11) NOT NULL,
  `id_agenda` INT(11) NOT NULL,
  `id_medico` INT(11) NOT NULL,
  `id_servico` INT(11) NOT NULL,
  `id_unidade` INT(11) NOT NULL,
  PRIMARY KEY (`id_agendamento`),
  UNIQUE KEY `uq_agendamento_agenda` (`id_agenda`),
  KEY `fk_agendamento_usuario` (`id_usuario`),
  KEY `fk_agendamento_medico` (`id_medico`),
  KEY `fk_agendamento_servico` (`id_servico`),
  KEY `fk_agendamento_unidade` (`id_unidade`),
  CONSTRAINT `fk_agendamento_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_agendamento_medico` FOREIGN KEY (`id_medico`) REFERENCES `medico` (`id_medico`) ON UPDATE CASCADE,
  CONSTRAINT `fk_agendamento_servico` FOREIGN KEY (`id_servico`) REFERENCES `servico_saude` (`id_servico`) ON UPDATE CASCADE,
  CONSTRAINT `fk_agendamento_unidade` FOREIGN KEY (`id_unidade`) REFERENCES `unidade_saude` (`id_unidade`) ON UPDATE CASCADE,
  CONSTRAINT `fk_agendamento_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `agendamento` (`id_agendamento`, `data_agendamento`, `horario`, `status`, `lembrete_ativo`, `id_usuario`, `id_agenda`, `id_medico`, `id_servico`, `id_unidade`) VALUES
(1, '2026-10-01', '08:30:00', 'pendente', 1, 1, 2, 1, 1, 1),
(2, '2026-10-02', '08:00:00', 'confirmado', 1, 2, 10, 1, 1, 1),
(3, '2026-10-05', '09:00:00', 'em_atendimento', 0, 3, 18, 1, 1, 1),
(4, '2026-10-01', '09:30:00', 'concluido', 0, 4, 24, 2, 1, 4),
(5, '2026-10-02', '14:00:00', 'cancelado', 0, 5, 31, 2, 2, 4),
(6, '2026-10-01', '14:00:00', 'confirmado', 1, 6, 36, 11, 4, 2),
(7, '2026-10-02', '08:00:00', 'pendente', 0, 7, 46, 21, 5, 7),
(8, '2026-10-01', '14:00:00', 'confirmado', 1, 8, 58, 41, 10, 12),
(9, '2026-10-01', '10:00:00', 'concluido', 0, 9, 66, 91, 19, 16);

-- --------------------------------------------------------
-- Ajuste fino dos contadores AUTO_INCREMENT
-- --------------------------------------------------------
ALTER TABLE `administrador` MODIFY `id_administrador` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `especialidade_medica` MODIFY `id_especialidade` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
ALTER TABLE `medico` MODIFY `id_medico` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;
ALTER TABLE `servico_saude` MODIFY `id_servico` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
ALTER TABLE `unidade_saude` MODIFY `id_unidade` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
ALTER TABLE `usuario` MODIFY `id_usuario` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
ALTER TABLE `agenda` MODIFY `id_agenda` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
ALTER TABLE `agendamento` MODIFY `id_agendamento` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

COMMIT;