# 🚀 Projeto: Aplicação PHP + MySQL com Docker Swarm

## 📌 Objetivo

Criar uma aplicação web simples em PHP para cadastro de usuários,
rodando em ambiente distribuído utilizando Docker Swarm.

A aplicação permite:

- Inserir usuários
- Listar usuários cadastrados
- Conectar aplicação PHP a banco MySQL rodando em outro nó do cluster

---

## 🏗️ Arquitetura

Cluster Docker Swarm com dois nós:

- VM1 → Apache + PHP (Service: apache)
- VM2 → MySQL 5.7 (Service: mysql-db)

Comunicação realizada via rede overlay do Swarm:

```
webnet
```

O host de conexão do banco dentro do cluster é:

```
mysql-db
```

---

## 🖥️ Ambiente

- Ubuntu Server
- Docker
- Docker Swarm
- Apache + PHP (imagem personalizada apache-php:1.0)
- MySQL 5.7

---

## 🔧 Passo 1 — Inicialização do Swarm

No nó manager:

```bash
docker swarm init
```

No segundo nó:

```bash
docker swarm join ...
```

---

## 🌐 Passo 2 — Criar rede overlay

```bash
docker network create --driver overlay webnet
```

---

## 🗄️ Passo 3 — Criar Service MySQL

```bash
docker service create \
  --name mysql-db \
  --network webnet \
  -e MYSQL_ROOT_PASSWORD=123456 \
  -e MYSQL_DATABASE=meubanco \
  --replicas 1 \
  mysql:5.7
```

Verificação:

```bash
docker service ls
```

---

## 🌍 Passo 4 — Criar Service Apache + PHP

```bash
docker service create \
  --name apache \
  --network webnet \
  --replicas 1 \
  --publish 80:80 \
  --mount type=bind,source=/home/vm2-docker/apache-site,target=/var/www/html \
  apache-php:1.0
```

---

## 🗃️ Passo 5 — Criar tabela no MySQL

Acessar container:

```bash
docker exec -it <ID_CONTAINER_MYSQL> bash
mysql -u root -p
```

Senha:

```
123456
```

Criar tabela:

```sql
USE meubanco;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 💻 Código da Aplicação

Conexão:

```php
$conn = new mysqli("mysql-db", "root", "123456", "meubanco");
```

A aplicação permite:

- Inserção via formulário
- Prepared statement para segurança
- Listagem automática ordenada por ID

---

## 🔍 Conceitos Aplicados

- Docker Swarm (cluster)
- Services
- Overlay network
- DNS interno do Swarm
- Bind mount
- Comunicação entre containers em nós diferentes
- MySQL + PHP mysqli
- Prepared statements

---

## ✅ Resultado Final

A aplicação roda acessando:

```
http://IP_DO_MANAGER/cadastro.php
```

Funcionalidades:

- Cadastro de usuários
- Persistência em banco MySQL
- Execução distribuída no cluster


## 👨‍💻 Autor

Projeto desenvolvido como laboratório prático de Docker Swarm e PHP.

