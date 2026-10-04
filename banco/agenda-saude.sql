CREATE DATABASE IF NOT EXISTS healthcare_plus
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE healthcare_plus;

CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    telefone VARCHAR(20),
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('Paciente','Medico','Administrador') DEFAULT 'Paciente',
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS unidades_saude (
    id_unidade INT AUTO_INCREMENT PRIMARY KEY,
    nome_unidade VARCHAR(100) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    cidade VARCHAR(100),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS especialidades (
    id_especialidade INT AUTO_INCREMENT PRIMARY KEY,
    nome_especialidade VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS medicos (
    id_medico INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_especialidade INT NOT NULL,
    id_unidade INT NOT NULL,
    crm VARCHAR(20) UNIQUE NOT NULL,
    CONSTRAINT fk_medico_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT fk_medico_especialidade
        FOREIGN KEY (id_especialidade) REFERENCES especialidades(id_especialidade),
    CONSTRAINT fk_medico_unidade
        FOREIGN KEY (id_unidade) REFERENCES unidades_saude(id_unidade)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS horarios (
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    id_medico INT NOT NULL,
    data_consulta DATE NOT NULL,
    horario TIME NOT NULL,
    disponivel BOOLEAN DEFAULT TRUE,
    CONSTRAINT fk_horario_medico
        FOREIGN KEY (id_medico) REFERENCES medicos(id_medico)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS agendamentos (
    id_agendamento INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_medico INT NOT NULL,
    id_horario INT NOT NULL,
    motivo VARCHAR(255),
    status ENUM('Agendada','Confirmada','Cancelada','Finalizada') DEFAULT 'Agendada',
    data_agendamento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_agendamento_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT fk_agendamento_medico
        FOREIGN KEY (id_medico) REFERENCES medicos(id_medico),
    CONSTRAINT fk_agendamento_horario
        FOREIGN KEY (id_horario) REFERENCES horarios(id_horario)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notificacoes (
    id_notificacao INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    mensagem TEXT NOT NULL,
    visualizada BOOLEAN DEFAULT FALSE,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notificacao_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS avaliacoes (
    id_avaliacao INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_medico INT NOT NULL,
    nota INT NOT NULL,
    comentario TEXT,
    data_avaliacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_nota CHECK (nota BETWEEN 1 AND 5),
    CONSTRAINT fk_avaliacao_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT fk_avaliacao_medico
        FOREIGN KEY (id_medico) REFERENCES medicos(id_medico)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS logs (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    acao VARCHAR(255),
    data_log TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_log_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
) ENGINE=InnoDB;

INSERT IGNORE INTO especialidades (id_especialidade, nome_especialidade) VALUES
(1, 'Clínico Geral'),
(2, 'Cardiologia'),
(3, 'Dermatologia'),
(4, 'Pediatria'),
(5, 'Ortopedia');

SELECT id_usuario, nome, cpf, email
FROM usuarios
WHERE cpf = '00000000000000';
