USE agenda_saude;

/* 1) Garante a coluna CRM do médico. */
SET @crm_existe := (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'medico' AND COLUMN_NAME = 'crm'
);
SET @sql := IF(
    @crm_existe = 0,
    'ALTER TABLE medico ADD COLUMN crm VARCHAR(30) NULL AFTER nome',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

UPDATE medico
SET crm = CONCAT('CRM-DEMO-', LPAD(id_medico, 5, '0'))
WHERE crm IS NULL OR crm = '';

SET @crm_index := (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'medico' AND INDEX_NAME = 'uq_medico_crm'
);
SET @sql := IF(
    @crm_index = 0,
    'ALTER TABLE medico ADD UNIQUE KEY uq_medico_crm (crm)',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

/* 2) Permite os estados usados pelo painel profissional. */
ALTER TABLE agendamento
MODIFY COLUMN status ENUM('confirmado','cancelado','pendente','em_atendimento','concluido') NOT NULL DEFAULT 'pendente';

/* 3) Cria/atualiza as únicas unidades permitidas. */
INSERT INTO unidade_saude (id_unidade, nome_unidade, endereco, localizacao, telefone) VALUES
(5, 'UBS Central', 'Rua São Paulo, 100 - Centro', 'São Joaquim da Barra/SP', '(16) 3728-1005'),
(6, 'Centro de Saúde Municipal', 'Rua Goiás, 250 - Centro', 'São Joaquim da Barra/SP', '(16) 3728-1006'),
(7, 'UBS Central de Franca', 'Rua Major Claudiano, 500 - Centro', 'Franca/SP', '(16) 3711-1007'),
(8, 'Centro de Especialidades de Franca', 'Av. Presidente Vargas, 1200 - Centro', 'Franca/SP', '(16) 3711-1008'),
(9, 'UBS Central de Ribeirão Preto', 'Rua Américo Brasiliense, 800 - Centro', 'Ribeirão Preto/SP', '(16) 3900-1009'),
(10, 'Centro Municipal de Especialidades', 'Av. Independência, 1500 - Centro', 'Ribeirão Preto/SP', '(16) 3900-1010'),
(11, 'UBS Central de Orlândia', 'Rua 6, 300 - Centro', 'Orlândia/SP', '(16) 3826-1011'),
(12, 'Centro de Saúde de Orlândia', 'Av. 2, 700 - Centro', 'Orlândia/SP', '(16) 3826-1012'),
(13, 'UBS Central de Uberaba', 'Rua Artur Machado, 900 - Centro', 'Uberaba/MG', '(34) 3331-1013'),
(14, 'Centro de Especialidades de Uberaba', 'Av. Leopoldino de Oliveira, 1800 - Centro', 'Uberaba/MG', '(34) 3331-1014')
ON DUPLICATE KEY UPDATE
    nome_unidade=VALUES(nome_unidade), endereco=VALUES(endereco),
    localizacao=VALUES(localizacao), telefone=VALUES(telefone);

/* 4) Mantém os horários antigos, mas troca Mossoró por unidades locais. */
UPDATE agenda SET id_unidade = 5 WHERE id_unidade = 1;
UPDATE agenda SET id_unidade = 7 WHERE id_unidade = 2;
UPDATE agenda SET id_unidade = 9 WHERE id_unidade = 3;
UPDATE agenda SET id_unidade = 11 WHERE id_unidade = 4;

/* 5) Remove os vínculos antigos e garante serviços sem duplicação. */
DELETE FROM unidade_servico WHERE id_unidade IN (1,2,3,4);
INSERT IGNORE INTO unidade_servico (id_unidade, id_servico)
SELECT u.id_unidade, s.id_servico
FROM unidade_saude u
CROSS JOIN servico_saude s
WHERE u.id_unidade BETWEEN 5 AND 14;

/* 6) Exclui definitivamente as unidades de Mossoró. */
DELETE FROM unidade_saude
WHERE localizacao LIKE 'Mossoró/RN%';

/* 7) Padroniza os serviços para Consulta/Retorno. */
UPDATE servico_saude
SET tipo_atendimento = CASE
    WHEN tipo_atendimento LIKE 'Consulta - %' THEN 'Consulta'
    WHEN tipo_atendimento LIKE 'Retorno - %' THEN 'Retorno'
    ELSE tipo_atendimento
END;
