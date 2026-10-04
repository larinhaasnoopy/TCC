-- Atualizações da base existente do AgendaSaúde

USE agenda_saude;

-- Adiciona o controle de lembrete apenas se a coluna ainda não existir.
SET @coluna_existe := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'agendamento'
      AND COLUMN_NAME = 'lembrete_ativo'
);

SET @sql := IF(
    @coluna_existe = 0,
    'ALTER TABLE agendamento ADD COLUMN lembrete_ativo TINYINT(1) NOT NULL DEFAULT 0 AFTER status',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Padroniza os nomes dos serviços. A especialidade continua relacionada
-- pelo campo id_especialidade.
UPDATE servico_saude
SET tipo_atendimento = CASE
    WHEN tipo_atendimento LIKE 'Consulta - %' THEN 'Consulta'
    WHEN tipo_atendimento LIKE 'Retorno - %' THEN 'Retorno'
    ELSE tipo_atendimento
END;

-- Novas unidades
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
    nome_unidade = VALUES(nome_unidade),
    endereco = VALUES(endereco),
    localizacao = VALUES(localizacao),
    telefone = VALUES(telefone);

-- Permite que as novas unidades ofereçam os serviços já cadastrados.
INSERT IGNORE INTO unidade_servico (id_unidade, id_servico)
SELECT u.id_unidade, s.id_servico
FROM unidade_saude u
CROSS JOIN servico_saude s
WHERE u.id_unidade BETWEEN 5 AND 14;

ALTER TABLE unidade_saude AUTO_INCREMENT = 15;
