-- ============================================================
-- Script de reparo isolado para a tabela `administrador`.
--
-- Use este script se aparecer o erro:
--   "SQLSTATE[42S02]: Base table or view not found: 1932
--    Table 'agenda_saude.administrador' doesn't exist in engine"
--
-- Esse erro normalmente acontece quando a importação do
-- banco (agenda_saude.sql) foi feita de forma parcial/interrompida,
-- ou quando os arquivos do InnoDB ficaram fora de sincronia
-- (comum ao copiar a pasta de dados do MySQL/MariaDB manualmente).
--
-- Este script recria só a tabela `administrador` do zero,
-- sem afetar as demais tabelas do banco.
-- ============================================================

USE `agenda_saude`;

DROP TABLE IF EXISTS `administrador`;

CREATE TABLE `administrador` (
  `id_administrador` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`id_administrador`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `administrador` (`nome`, `email`, `senha`) VALUES
('Administrador Geral', 'admin@agendasaude.com', 'admin123'),
('Suporte Técnico', 'suporte@agendasaude.com', 'admin123');

-- Após rodar este script, o login admin@agendasaude.com / admin123
-- volta a funcionar (a senha é convertida para hash automaticamente
-- no primeiro login).
