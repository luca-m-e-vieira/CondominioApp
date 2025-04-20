@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Criar Novo Usuário
            <a href="{{ route('users.index') }}" class="btn btn-secondary float-end">Voltar</a>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nome*</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email*</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Senha*</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Tipo de Usuário*</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Selecione o tipo</option>
                        <option value="admin">Administrador</option>
                        <option value="sindico">Síndico</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Criar Usuário</button>
            </form>
        </div>
    </div>
</div>
@endsection