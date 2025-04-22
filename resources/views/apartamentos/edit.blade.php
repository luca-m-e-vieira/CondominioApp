@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Editar Apartamento: {{ $apartamento->numero }}
            <a href="{{ route('apartamentos.index') }}" class="btn btn-secondary float-end">Voltar</a>
        </div>
        
        <div class="card-body">
            <form method="POST" action="{{ route('apartamentos.update', $apartamento) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="numero" class="form-label">Número*</label>
                    <input type="text" class="form-control" id="numero" name="numero" 
                           value="{{ old('numero', $apartamento->numero) }}" required>
                </div>
                @if(auth()->user()->role === 'admin')
                            
                <div class="mb-3">
                    <label for="condominio_id" class="form-label">Condomínio*</label>
                    <select class="form-select" id="condominio_id" name="condominio_id" required>
                   
                            
                        
                        <option value="">Selecione um condomínio</option>
                        @foreach($condominios as $condominio)
                            <option value="{{ $condominio->id }}" 
                                {{ $apartamento->condominio_id == $condominio->id ? 'selected' : '' }}>
                                {{ $condominio->nome }}
                            </option>
                        @endforeach
                    </select>
                    
                </div>
                @endif
                <div class="mb-3">
                    <label for="morador_id" class="form-label">Morador</label>
                    <select class="form-select" id="morador_id" name="morador_id">
                        <option value="">Nenhum morador vinculado</option>
                        @foreach($moradores as $morador)
                            <option value="{{ $morador->id }}"
                                {{ $apartamento->morador_id == $morador->id ? 'selected' : '' }}>
                                {{ $morador->nome }} ({{ $morador->condominio->nome }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Atualizar Apartamento</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const condominioSelect = document.getElementById('condominio_id');
    const moradorSelect = document.getElementById('morador_id');
    const currentMoradorId = "{{ $apartamento->morador_id }}"; // Mantém o morador atual

    condominioSelect.addEventListener('change', function() {
        if (this.value) {
            fetch(`/api/condominios/${this.value}/moradores`)
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="">Nenhum morador vinculado</option>';
                    
                    data.forEach(morador => {
                        const selected = morador.id == currentMoradorId ? 'selected' : '';
                        options += `<option value="${morador.id}" ${selected}>${morador.nome}</option>`;
                    });
                    
                    moradorSelect.innerHTML = options;
                });
        } else {
            moradorSelect.innerHTML = '<option value="">Nenhum morador vinculado</option>';
        }
    });
});
</script>
@endsection
