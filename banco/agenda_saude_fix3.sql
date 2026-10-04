-- =====================================================
-- Adiciona os status "em_atendimento" e "concluido"
-- usados no painel do médico. Seguro rodar de novo.
-- =====================================================

USE agenda_saude;

ALTER TABLE agendamento
    MODIFY COLUMN status ENUM('pendente','confirmado','em_atendimento','concluido','cancelado')
    NOT NULL DEFAULT 'pendente';
