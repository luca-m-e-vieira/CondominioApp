# Sistema de Gestão de Condomínios

Sistema para gestão de condomínios com controle de moradores, apartamentos e síndicos.

## 🚀 Funcionalidades
- **Admin**: CRUD completo de condomínios, usuários, moradores e apartamentos
- **Síndico**: Gerenciar moradores e apartamentos do seu condomínio
- **Expulsão de moradores**
- **Vínculo automático entre entidades**

## ⚙️ Instalação
```bash
git clone [URL_DO_REPO]
cd nome-do-repo
composer install
cp .env.example .env
php artisan key:generate
```

## 📋 Configuração
1. Configure o `.env` com suas credenciais do banco de dados
2. Execute:
```bash
php artisan migrate --seed
php artisan serve
```

## 🧪 Contas para Teste
- **Admin**: `admin@example.com` / `password`
- **Síndico**: `sindico@example.com` / `password`