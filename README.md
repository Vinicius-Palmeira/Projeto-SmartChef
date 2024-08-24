# Configuração do Projeto

## Arquivos a Modificar

### `SRC/conexao.php`

Este arquivo contém a configuração de conexão com o banco de dados. Verifique as credenciais e as configurações do banco de dados neste arquivo.

### `SRC/sql.php`

Este arquivo deve ser atualizado com as instruções SQL necessárias para a configuração do banco de dados.

## Instruções SQL

```sql
DROP USER IF EXISTS 'root'@'%';
CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY 'aluno';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
SELECT User, Host FROM mysql.user;
```

## Gerar Código QR

Para criar um código QR, utilize a seguinte URL da API:

```
https://api.qrserver.com/v1/create-qr-code/?data=[URL-encoded-text]&size=500x500
```

Substitua `[URL-encoded-text]` pelo texto codificado que deseja incluir no código QR.
