@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Editar Morador: {{ $morador->nome }}</h2>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('moradores.update', $morador->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome *</label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                                   id="nome" name="nome" value="{{ old('nome', $morador->nome) }}" required>
                            @error('nome')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $morador->email) }}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>



@if(auth()->user()->role === 'admin')
<div class="mb-3">
    <label for="condominio_id" class="form-label">Condomínio *</label>
    <select class="form-control @error('condominio_id') is-invalid @enderror" 
            id="condominio_id" name="condominio_id" required>
        @foreach($condominios as $condominio)
            <option value="{{ $condominio->id }}" 
                {{ $morador->condominio_id == $condominio->id ? 'selected' : '' }}>
                {{ $condominio->nome }}
            </option>
        @endforeach
    </select>
</div>
@endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('moradores.index') }}" class="btn btn-secondary">
                                Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Atualizar Morador
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const expulsoCheckbox = document.getElementById('expulso');
        const condominioSelect = document.getElementById('condominio_id');
        
        if (expulsoCheckbox && condominioSelect) {
            expulsoCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    condominioSelect.value = '';
                    condominioSelect.disabled = true;
                } else {
                    condominioSelect.disabled = false;
                }
            });
            
            // Inicializar estado
            if (expulsoCheckbox.checked) {
                condominioSelect.disabled = true;
            }
        }
    });
</script>
@endsection