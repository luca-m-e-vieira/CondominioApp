@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Adicionar Novo Apartamento
            <a href="{{ route('apartamentos.index') }}" class="btn btn-secondary float-end">Voltar</a>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('apartamentos.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="numero" class="form-label">Número do Apartamento</label>
                    <input type="text" class="form-control" id="numero" name="numero" required>
                </div>

@if(auth()->user()->role === 'admin')
    <div class="mb-3">
        <label for="condominio_id" class="form-label">Condomínio*</label>
        <select class="form-select" id="condominio_id" name="condominio_id" required>
            <option value="">Selecione um condomínio</option>
            @foreach($condominios as $condominio)
                <option value="{{ $condominio->id }}">{{ $condominio->nome }}</option>
            @endforeach
        </select>
    </div>
@else
    @if($condominios->isNotEmpty())
        <input type="hidden" name="condominio_id" value="{{ $condominios->first()->id }}">
        <div class="mb-3">
            <label class="form-label">Condomínio</label>
            <input type="text" class="form-control" 
                   value="{{ $condominios->first()->nome }}" readonly>
        </div>
    @endif
@endif

                <div class="mb-3" id="morador-container">
                    <label for="morador_id" class="form-label">Morador</label>
                    <select class="form-select" id="morador_id" name="morador_id">
                        <option value="">Selecione um morador (opcional)</option>
                        @if(isset($moradores))
                            @foreach($moradores as $morador)
                                <option value="{{ $morador->id }}">{{ $morador->nome }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Cadastrar Apartamento</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const condominioSelect = document.getElementById('condominio_id');
    const moradorSelect = document.getElementById('morador_id');
    const moradorContainer = document.getElementById('morador-container');

    condominioSelect.addEventListener('change', function() {
        if (this.value) {
            // Busca moradores do condomínio selecionado
            fetch(`/api/condominios/${this.value}/moradores`)
                .then(response => response.json())
                .then(data => {
                    moradorSelect.innerHTML = '<option value="">Selecione um morador (opcional)</option>';
                    data.forEach(morador => {
                        const option = document.createElement('option');
                        option.value = morador.id;
                        option.textContent = morador.nome;
                        moradorSelect.appendChild(option);
                    });
                });
        } else {
            moradorSelect.innerHTML = '<option value="">Selecione um morador (opcional)</option>';
        }
    });
});
</script>
@endsection