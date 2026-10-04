-- Atualização da tabela de médicos para permitir login no painel profissional.
-- Execute este arquivo uma única vez no banco agenda_saude.

USE agenda_saude;

ALTER TABLE medico
    ADD COLUMN email VARCHAR(150) NULL AFTER nome,
    ADD COLUMN senha VARCHAR(255) NULL AFTER email;

UPDATE medico
SET email = CONCAT('medico', id_medico, '@agendasaude.com'),
    senha = '$2y$12$0pYsbzUU6s/yWSUt8.H.jOVHp5sIypkwL3E30PTfmKEwyyYCORJB.'
WHERE email IS NULL OR senha IS NULL;

ALTER TABLE medico
    MODIFY email VARCHAR(150) NOT NULL,
    MODIFY senha VARCHAR(255) NOT NULL,
    ADD UNIQUE KEY email (email);

-- Senha padrão dos médicos inseridos neste projeto: medico123
