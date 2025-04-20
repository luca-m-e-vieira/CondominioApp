@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lista de Condomínios</h5>
            <a href="{{ route('condominios.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Novo Condomínio
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="25%">Condomínio</th>
                            <th width="25%">Endereço</th>
                            <th width="20%">Síndico Ativo</th>
                            <th width="15%">Apartamentos</th>
                            <th width="15%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($condominios as $condominio)
                            <tr>
                                <td>
                                    <strong>{{ $condominio->nome }}</strong>
                                </td>
                                <td>{{ $condominio->endereco }}</td>
                                <td>
                                    @if($condominio->sindicoAtivo())
                                        <span class="badge bg-success">
                                            <i class="fas fa-user-shield"></i> {{ $condominio->sindicoAtivo()->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Sem síndico ativo</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        Total: {{ $condominio->apartamentos_count ?? 0 }}
                                    </span>
                                    <span class="badge bg-danger">
                                        Vagos: {{ $condominio->apartamentos_vagos_count ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('condominios.edit', $condominio) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="fas fa-edit">Editar</i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info" 
                                                title="Detalhes"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailsModal{{ $condominio->id }}">
                                            <i class="fas fa-info-circle">Detalhes</i>
                                        </button>
                                        <form action="{{ route('condominios.destroy', $condominio->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" 
            onclick="return confirm('ATENÇÃO! Esta ação irá excluir permanentemente:\n- O condomínio\n- Todos os apartamentos\n- Vínculos com síndicos\n\nDeseja continuar?')">
        <i class="fas fa-trash"></i> Excluir
    </button>
</form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal de Detalhes -->
                            <div class="modal fade" id="detailsModal{{ $condominio->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detalhes: {{ $condominio->nome }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h6><i class="fas fa-map-marker-alt"></i> Endereço:</h6>
                                                    <p>{{ $condominio->endereco }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6><i class="fas fa-user-shield"></i> Síndico:</h6>
                                                    <p>
                                                        @if($condominio->sindicoAtivo())
                                                            {{ $condominio->sindicoAtivo()->name }} ({{ $condominio->sindicoAtivo()->email }})
                                                        @else
                                                            <span class="text-muted">Nenhum síndico ativo</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6><i class="fas fa-building"></i> Apartamentos:</h6>
                                                    <ul class="list-group">
                                                        @forelse($condominio->apartamentos as $apartamento)
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                {{ $apartamento->numero }}
                                                                @if($apartamento->morador)
                                                                    <span class="badge bg-success">Ocupado</span>
                                                                @else
                                                                    <span class="badge bg-secondary">Vago</span>
                                                                @endif
                                                            </li>
                                                        @empty
                                                            <li class="list-group-item">Nenhum apartamento cadastrado</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
<div class="col-md-6">
    <h6><i class="fas fa-users"></i> Moradores:</h6>
    <ul class="list-group">
        @php
            // Agrupa moradores por nome (incluindo os sem apartamento)
            $moradoresAgrupados = $condominio->todosMoradores
                ->groupBy('nome')
                ->map(function($moradores) {
                    $morador = $moradores->first();
                    $apartamentos = $moradores->flatMap(function($m) {
                        return $m->apartamentos ?? collect();
                    })->pluck('numero')->unique()->implode(', ');
                    
                    return (object) [
                        'nome' => $morador->nome,
                        'apartamentos' => $apartamentos ?: 'Nenhum',
                        'tem_apartamento' => $apartamentos !== 'Nenhum'
                    ];
                });
        @endphp
        
        @forelse($moradoresAgrupados as $morador)
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <span>{{ $morador->nome }}</span>
                    @if(!$morador->tem_apartamento)
                        <span class="badge bg-warning">Sem apartamento</span>
                    @endif
                </div>
                @if($morador->tem_apartamento)
                    <div class="mt-1">
                        <small class="text-muted">
                            <i class="fas fa-building"></i> Apartamentos: {{ $morador->apartamentos }}
                        </small>
                    </div>
                @endif
            </li>
        @empty
            <li class="list-group-item">Nenhum morador cadastrado</li>
        @endforelse
    </ul>
</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Nenhum condomínio cadastrado
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        font-weight: 500;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge {
        font-size: 0.75em;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Ativar tooltips
    $(function () {
        $('[title]').tooltip();
    });
</script>
@endpush