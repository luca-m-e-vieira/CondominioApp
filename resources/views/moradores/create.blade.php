@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Adicionar Novo Morador
            <a href="{{ route('moradores.index') }}" class="btn btn-secondary float-end">Voltar</a>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('moradores.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="nome" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                                @if(auth()->user()->role === 'admin')
                    <div class="mb-3">
                        <label for="condominio_id" class="form-label">Condomínio</label>
                        <select class="form-select" id="condominio_id" name="condominio_id">
                            <option value="">Selecione um condomínio (opcional)</option>
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
                            <input type="text" class="form-control" value="{{ $condominios->first()->nome }}" readonly>
                        </div>
                    @endif
                @endif

                <button type="submit" class="btn btn-primary">Cadastrar Morador</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const condominioSelect = document.getElementById('condominio_id');
    const apartamentoContainer = document.getElementById('apartamento-container');
    const apartamentoSelect = document.getElementById('apartamento_id');

    condominioSelect.addEventListener('change', function() {
        if (this.value) {
            // Busca apartamentos vagos do condomínio selecionado
            fetch(`/api/condominios/${this.value}/apartamentos-vagos`)
                .then(response => response.json())
                .then(data => {
                    apartamentoSelect.innerHTML = '<option value="">Selecione um apartamento (opcional)</option>';
                    data.forEach(apartamento => {
                        const option = document.createElement('option');
                        option.value = apartamento.id;
                        option.textContent = apartamento.numero;
                        apartamentoSelect.appendChild(option);
                    });
                    apartamentoContainer.style.display = 'block';
                });
        } else {
            apartamentoContainer.style.display = 'none';
        }
    });
});
</script>
@endsection