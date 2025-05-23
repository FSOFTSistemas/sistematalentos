
# 📘 Sistema de Gestão de Empresas com Planos

Este projeto é um sistema Laravel com foco na gestão de empresas e planos, permitindo controle de usuários com permissões, visualização, criação, edição e exclusão de empresas, planos e usuários administradores. Utiliza o AdminLTE para layout e a biblioteca **Spatie Permission** para controle de permissões e papéis.

---

## ✅ Funcionalidades

- Cadastro de empresas com informações administrativas.
- Seleção e vinculação de planos com valores e periodicidade.
- Criação automática de usuário administrador para a empresa.
- Painel com menu restrito baseado em permissões (`master`).
- Visualização e edição de empresas.
- Permissões com Spatie.
- Exclusão em cascata de usuários ao apagar uma empresa.

---

## 🚀 Instalação

```bash
# Clonar o repositório
git clone https://github.com/seu-usuario/seu-repositorio.git

# Acessar o diretório
cd seu-repositorio

# Instalar dependências
composer install

# Configurar .env e gerar key
cp .env.example .env
php artisan key:generate

# Migrar o banco de dados
php artisan migrate

# Rodar os seeders (cria planos e usuário master)
php artisan db:seed
```

---

## 🧩 Seeders Importantes

```bash
php artisan db:seed
```

Cria:

- Planos pré-definidos:
  - Básico
  - Intermediário
  - Avançado

- Usuário Master:
  - Email: `master@ecclesia.com`
  - Senha: `password`
  - Permissão: `master`

---

## 🛠️ Comandos Úteis

```bash
# Rodar todas as migrations
php artisan migrate

# Reverter migrations
php artisan migrate:rollback

# Rodar seeders individualmente
php artisan db:seed --class=PlanoSeeder
php artisan db:seed --class=UserSeeder

# Criar novo seeder
php artisan make:seeder NomeDoSeeder

# Criar nova migration
php artisan make:migration create_tabela

# Criar novo model com migration e controller
php artisan make:model Empresa -mc
```

---

## 🔐 Permissões com Spatie

- Roles usados:
  - `master` → Acesso total, vê todos os menus e funcionalidades restritas.

- Menu com `can`:
  ```php
  [
    'text' => 'Planos',
    'url' => '/planos',
    'can' => 'master'
  ]
  ```

- Verificação com Gate:
  ```blade
  @can('master')
    // conteúdo restrito
  @endcan
  ```

---

## ⚠️ Observações

- A exclusão de uma empresa remove automaticamente os usuários associados (via relacionamento com `onDelete('cascade')`).
- Os modais foram substituídos por páginas completas (`create`, `edit`, `show`).

---

## 📂 Estrutura Recomendada

- `app/Models/Empresa.php` – model de empresas.
- `app/Http/Controllers/EmpresaController.php` – CRUD completo.
- `database/seeders/PlanoSeeder.php` – planos base.
- `resources/views/empresas/` – telas `index`, `create`, `edit`, `show`.

---

Se quiser, posso gerar esse arquivo como um `.md` pra você baixar. Deseja isso?
