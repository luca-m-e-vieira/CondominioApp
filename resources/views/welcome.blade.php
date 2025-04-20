<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nome do Sistema</title>
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <style>
            body {
                font-family: 'Figtree', sans-serif;
                background-color: #f8fafc;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .container {
                text-align: center;
                background-color: #ffffff;
                padding: 2rem;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            h1 {
                color: #1f2937;
                font-size: 2.5rem;
                margin-bottom: 1.5rem;
            }
            p {
                color: #6b7280;
                margin-bottom: 2rem;
            }
            .btn-login {
                padding: 10px 20px;
                font-size: 1.125rem;
                background-color: #3b82f6;
                color: #ffffff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
            .btn-login:hover {
                background-color: #2563eb;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Sisitema de Condominios</h1>
            <p>Bem-vindo ao nosso sistema. Clique no botão abaixo para acessar o login.</p>
            <a href="{{ route('login') }}">
                <button class="btn-login">Ir para Login</button>
            </a>
        </div>
    </body>
</html>
