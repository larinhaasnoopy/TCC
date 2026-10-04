# HealthCare+

Projeto inicial do TCC de sistema de agendamento de consultas.

## Estrutura

- index.php — página inicial
- cadastro.php — cadastro de pacientes
- login.php — autenticação
- logout.php — encerra a sessão
- teste.php — teste da conexão
- conexao/ — conexão MySQL
- controller/ — processamento dos formulários
- paciente/ — área do paciente
- admin/ — área administrativa
- includes/ — componentes compartilhados
- css/ — estilos
- banco/healthcare_plus.sql — estrutura do banco

## Instalação

1. Coloque a pasta `HEALTHCARE-PLUS` em `C:\xampp\htdocs\`.
2. Inicie Apache e MySQL no XAMPP.
3. No phpMyAdmin, confirme que o banco `healthcare_plus` existe.
4. Abra `http://localhost/HEALTHCARE-PLUS/teste.php`.
5. Se aparecer "Conexão realizada com sucesso!", abra `http://localhost/HEALTHCARE-PLUS/`.

## Login administrativo

O banco já existente de vocês pode ter um administrador. Se ele tiver sido criado com senha em texto puro, o login deste projeto aceita essa senha uma vez e a converte automaticamente para um hash seguro.

Se precisarem criar um administrador novo, alterem o tipo de um usuário existente no phpMyAdmin:
`UPDATE usuarios SET tipo_usuario = 'Administrador' WHERE email = 'email_do_usuario';`

