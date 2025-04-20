@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Editar Usuário: {{ $user->name }}
            <a href="{{ route('users.index') }}" class="btn btn-secondary float-end">Voltar</a>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nome*</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email*</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Nova Senha</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <small class="text-muted">Deixe em branco para manter a senha atual</small>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Tipo de Usuário*</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="sindico" {{ $user->role == 'sindico' ? 'selected' : '' }}>Síndico</option>
                    </select>
                </div>

                @if($user->role === 'sindico')
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> O vínculo com condomínios pode ser gerenciado na lista de usuários
                </div>
                @endif

                <button type="submit" class="btn btn-primary">Atualizar Usuário</button>
            </form>
        </div>
    </div>
</div>
@endsection