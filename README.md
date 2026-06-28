# Sistema de Tarefas POO

Projeto da disciplina de Linguagens de Programação, parte de Orientação a Objetos.

O sistema permite cadastrar usuários, fazer login e gerenciar tarefas pessoais com prioridade, data limite e status.

## Como rodar

Na primeira vez, execute:

```bash
docker compose up -d --build
```

Nas próximas vezes:

```bash
docker compose up -d
```

Sistema:

```text
http://localhost:8080
```

phpMyAdmin:

```text
http://localhost:8081
```

Para parar:

```bash
docker compose down
```
