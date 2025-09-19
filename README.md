# To‑Do API (Laravel + JWT + Swagger)

Backend de uma aplicação **To‑Do** com autenticação **JWT**, CRUD completo de tarefas (incluindo **toggle** de conclusão), usuários, paginação, validação, documentação **OpenAPI/Swagger** e suíte de testes com **Pest**.

---

## Sumário
- [Visão geral](#visão-geral)
- [Principais funcionalidades](#principais-funcionalidades)
- [Stack & decisões](#stack--decisões)
- [Estrutura do projeto](#estrutura-do-projeto)
- [Requisitos](#requisitos)
- [Como rodar (Docker/Sail)](#como-rodar-dockersail)
- [Como rodar (sem Docker)](#como-rodar-sem-docker)
- [Banco de dados & migrations](#banco-de-dados--migrations)
- [Autenticação JWT](#autenticação-jwt)
- [Documentação Swagger](#documentação-swagger)
- [Testes](#testes)
- [Endpoints](#endpoints)
- [Exemplos de uso (curl)](#exemplos-de-uso-curl)

---

## Visão geral
Aplicação API REST que expõe:
- Rotas de **auth** (registro, login, me, refresh, logout);
- Rotas de **todos** (CRUD + toggle de `is_completed`), com paginação e validação;
- Rotas de **users** (CRUD + listas auxiliares), protegidas por JWT.

A documentação é exposta via **Swagger UI** e os testes cobrem controladores e serviços.

---

## Principais funcionalidades
- **Auth JWT (tymon/jwt-auth)** – emissão de tokens, refresh e proteção das rotas.
- **To‑Dos** – criar, listar, atualizar, deletar e **marcar/desmarcar** (`toggle`).
- **Users** – endpoints de suporte (ex.: listagens) + CRUD.
- **Paginação** – `?per_page=` nos listagens.
- **Validação** – Form Requests por endpoint.
- **Swagger/OpenAPI** – documentação interativa em `/api/documentation`.
- **Testes (Pest)** – feature e unit, com SQLite em memória.

---

## Stack & decisões
- **PHP** (Laravel)
- **MySQL** (produção/dev via Docker) e **SQLite** (testes)
- **Laravel Sail** para ambiente Docker
- **JWT**: `tymon/jwt-auth` (Lcobucci; HS256)
- **Swagger**: `darkaonline/l5-swagger`
- **Testes**: `pestphp/pest`

**Decisões:**
- Usar **Sail** para padronizar dev com MySQL no serviço `mysql`.
- Nos **testes**, usar **SQLite em memória** para velocidade e isolamento.
- **Swagger** com `@OA\...` annotations nos controllers e arquivo base `app/Swagger/OpenApi.php`.

---

## Estrutura do projeto
```
app/
  Http/
    Controllers/
      Api/V1/
        AuthController.php
        TodoController.php
        UserController.php
  Swagger/
    OpenApi.php              # Info, Security, Servers, Schemas
config/
  l5-swagger.php
routes/
  v1/api.php                 # Rotas da API (prefixo api/v1)
tests/
  Feature/
    Http/Controllers/
      AuthControllerTest.php
      UserControllerTest.php
    TodoHttpTest.php
  Unit/
    Services/
      UserServiceTest.php
      TodoServiceTest.php
```

---

## Requisitos
- Docker + Docker Compose **ou**
- PHP 8.x, Composer, MySQL 8.x

---

## Como rodar (Docker/Sail)
1. **Instalar dependências**
   ```bash
   ./vendor/bin/sail composer install
   ```
2. **Subir containers**
   ```bash
   ./vendor/bin/sail up -d
   ```
3. **Copiar .env** e ajustar o DB para uso em container:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=mysql      # nome do serviço no docker
   DB_PORT=3306
   DB_DATABASE=todo_api   # ou "sail"
   DB_USERNAME=sail
   DB_PASSWORD=password
   ```
4. **App key & JWT secret**
   ```bash
   ./vendor/bin/sail artisan key:generate
   ./vendor/bin/sail artisan jwt:secret --force --no-interaction
   ```
5. **Migrations**
   ```bash
   ./vendor/bin/sail artisan migrate
   ```
6. **(Opcional) Swagger sempre gerando em dev**: no `.env`
   ```env
   L5_SWAGGER_GENERATE_ALWAYS=true
   ```
7. **Gerar docs** (se não usar o flag acima)
   ```bash
   ./vendor/bin/sail artisan l5-swagger:generate
   ```
8. **Acessar**
   - API: `http://localhost`
   - Swagger UI: `http://localhost/api/documentation`

> Se preferir usar `DB_DATABASE=sail`, não esqueça de rodar as migrations. Para outro nome, crie o DB: `./vendor/bin/sail mysql -u sail -ppassword -e "CREATE DATABASE IF NOT EXISTS todo_api;"`.

---

## Como rodar (sem Docker)
1. `composer install`
2. `cp .env.example .env`
3. Ajuste o DB (MySQL local):
   ```env
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=todo_api
   DB_USERNAME=seu_user
   DB_PASSWORD=seu_pass
   ```
4. `php artisan key:generate`
5. `php artisan jwt:secret --force --no-interaction`
6. `php artisan migrate`
7. Servidor: `php artisan serve` → `http://127.0.0.1:8000`

> Se alternar entre Docker e local, lembre-se de ajustar `DB_HOST` (container: `mysql`; local: `127.0.0.1`).

---

## Banco de dados & migrations
- As migrations criam as tabelas necessárias (`users`, `todos`, etc.).
- Paginação nas listagens com `?per_page=15` (padrão) e demais metadados no envelope.

---

## Autenticação JWT
- **Registro**: `POST /api/v1/auth/register`
- **Login**: `POST /api/v1/auth/login` → retorna `access_token`, `token_type`, `expires_in` e `user`.
- **Me**: `GET /api/v1/auth/me` (Bearer Token)
- **Refresh**: `GET /api/v1/auth/refresh` (Bearer Token)
- **Logout**: `GET /api/v1/auth/logout` (Bearer Token)

**Header de autorização:**
```
Authorization: Bearer SEU_TOKEN
```

---

## Documentação Swagger
- Pacote: **L5-Swagger** (`darkaonline/l5-swagger`).
- Arquivo base: `app/Swagger/OpenApi.php` (contém `@OA\Info`, `@OA\Server`, `@OA\SecurityScheme`, e Schemas úteis).
- Controllers anotados com `use OpenApi\Annotations as OA;` e `@OA\Get`, `@OA\Post`, etc.
- Gerar docs:
  ```bash
  php artisan l5-swagger:generate
  # ou com Sail
  ./vendor/bin/sail artisan l5-swagger:generate
  ```
- UI: `http://localhost/api/documentation`
- JSON: `http://localhost/api/documentation/json`
- Para testar endpoints protegidos: clique em **Authorize** → selecione `bearerAuth` → cole o token do **/auth/login**.

---

## Testes
- **Runner**:
  ```bash
  php artisan test
  # ou
  ./vendor/bin/sail artisan test
  ```
- **Banco de testes**: **SQLite em memória** via `phpunit.xml`:
  ```xml
  <php>
      <server name="APP_ENV" value="testing"/>
      <server name="DB_CONNECTION" value="sqlite"/>
      <server name="DB_DATABASE" value=":memory:"/>
      <server name="DB_FOREIGN_KEYS" value="true"/>
      <server name="JWT_SECRET" value="changeme_testing_secret"/>
  </php>
  ```
- Opcional: `.env.testing` coerente com o acima. Lembre: **phpunit.xml tem precedência**.
- Sugestão (Pest): aplicar `RefreshDatabase` em `tests/Pest.php`:
  ```php
  <?php
  use Illuminate\Foundation\Testing\RefreshDatabase;
  uses(RefreshDatabase::class)->in('Feature', 'Unit');
  ```

---

## Endpoints
### Auth
- `POST /api/v1/auth/register`
- `POST /api/v1/auth/login`
- `GET  /api/v1/auth/me` *(Bearer)*
- `GET  /api/v1/auth/refresh` *(Bearer)*
- `GET  /api/v1/auth/logout` *(Bearer)*

### Todos *(Bearer)*
- `GET    /api/v1/todos`
- `POST   /api/v1/todos`
- `GET    /api/v1/todos/{id}`
- `PATCH  /api/v1/todos/{id}`
- `DELETE /api/v1/todos/{id}`
- `PATCH  /api/v1/todos/{id}/toggle`

### Users *(Bearer)*
- `GET    /api/v1/users`
- `POST   /api/v1/users`
- `GET    /api/v1/users/{id}`
- `PATCH  /api/v1/users/{id}`
- `DELETE /api/v1/users/{id}`
- Auxiliares: `GET /api/v1/users/active`, `GET /api/v1/users/all`

---

## Exemplos de uso (curl)
**Registrar**
```bash
curl -X POST http://localhost/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name":"Caio",
    "email":"caio@example.com",
    "password":"secret123",
    "password_confirmation":"secret123"
  }'
```

**Login**
```bash
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"caio@example.com","password":"secret123"}'
```

> Guarde o `access_token` para usar como Bearer nas próximas chamadas.

**Criar To‑Do**
```bash
curl -X POST http://localhost/api/v1/todos \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title":"Escrever testes","description":"Cobrir service e HTTP","due_date":"2025-09-22"}'
```

**Listar To‑Dos**
```bash
curl -H "Authorization: Bearer $TOKEN" \
  "http://localhost/api/v1/todos?per_page=10"
```

**Toggle**
```bash
curl -X PATCH http://localhost/api/v1/todos/1/toggle \
  -H "Authorization: Bearer $TOKEN"
```

---

## Licença
Uso acadêmico/avaliativo.
