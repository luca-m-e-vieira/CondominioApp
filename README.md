# Sistema de Gestão de Condomínios

Sistema para gestão de condomínios com controle de moradores, apartamentos e síndicos.

## 🚀 Funcionalidades
- **Admin**: CRUD completo de condomínios, usuários, moradores e apartamentos
- **Síndico**: Gerenciar moradores e apartamentos do seu condomínio
- **Expulsão de moradores**
- **Vínculo automático entre entidades**

## ⚙️ Instalação

⚠️ **Atenção**: Este projeto utiliza MySQL como banco de dados padrão.

### Opção 1: Usando PHP Artisan Serve (Local)
```bash
git clone https://github.com/luca-m-e-vieira/CondominioApp.git
```
```bash
cd CondominioApp
```
```bash
composer install
```
```bash
cp .env.example .env
```
```bash
php artisan key:generate
```

Configurar o `.env` com suas credenciais do PostgreSQL:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

Executar as migrações:
```bash
php artisan migrate --seed
```
```bash
php artisan serve
```
### Opção 2: Usando Laravel Sail (Docker)
```bash
git clone https://github.com/luca-m-e-vieira/CondominioApp.git
```
```bash
cd CondominioApp
```
```bash
composer install
```
```bash
cp .env.example .env
```
```bash
php artisan key:generate
```

Configurar o `.env` para usar o Sail:
```bash
DB_CONNECTION=mysql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

Iniciar o ambiente com Sail:
```bash
./vendor/bin/sail up -d
```
```bash
./vendor/bin/sail artisan migrate --seed
```

## 🧪 Contas para Teste
- **Admin**:
 ```bash
 admin@condominios.com
```
senha:
```bash
12345678
```
- **Síndico**:
escolha um sindico
```bash
sindico1@condominios.com
```
```bash
sindico2@condominios.com
```
```bash
sindico3@condominios.com
```
```bash
sindico4@condominios.com
```
senha:
```bash
12345678
```
