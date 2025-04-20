# Sistema de Gestão de Condomínios

Sistema para gestão de condomínios com controle de moradores, apartamentos e síndicos.

## 🚀 Funcionalidades
- **Admin**: CRUD completo de condomínios, usuários, moradores e apartamentos
- **Síndico**: Gerenciar moradores e apartamentos do seu condomínio
- **Expulsão de moradores**
- **Vínculo automático entre entidades**

## ⚙️ Instalação
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

## 📋 Configuração
1. Configure o `.env` com suas credenciais do banco de dados
2. Execute:
```bash
php artisan migrate --seed
```
```bash
php artisan serve
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
```bash
sindico1@condominios.com
```
senha:
```bash
12345678
```

