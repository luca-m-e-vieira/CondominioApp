@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gerenciamento de Usuários</h2>
        <a href="{{ route('users.create') }}" class="btn btn-success" title="Criar Novo Usuário">
            <i class="fas fa-plus-circle"></i> Novo Usuário
        </a>
    </div>

    <!-- Abas para Síndicos e Administradores -->
    <ul class="nav nav-tabs mb-4" id="userTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="sindicos-tab" data-bs-toggle="tab" data-bs-target="#sindicos" type="button" role="tab">
                Síndicos <span class="badge bg-primary">{{ $sindicos->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="admins-tab" data-bs-toggle="tab" data-bs-target="#admins" type="button" role="tab">
                Administradores <span class="badge bg-secondary">{{ $admins->count() }}</span>
            </button>
        </li>
    </ul>

    <div class="tab-content" id="userTabsContent">
        <!-- Tab Síndicos -->
        <div class="tab-pane fade show active" id="sindicos" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="20%">Síndico</th>
                                    <th width="40%">Condomínios Vinculados</th>
                                    <th width="20%">Vincular Novo</th>
                                    <th width="20%">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sindicos as $sindico)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-user-shield fa-lg text-primary me-2"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $sindico->name }}</h6>
                                                <small class="text-muted">{{ $sindico->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @forelse($sindico->condominios as $condominio)
                                        <div class="d-inline-block me-2 mb-2">
                                            <div class="d-flex align-items-center">
                                                <form action="{{ route('sindicos.toggle-active', $sindico) }}" method="POST" class="me-1">
                                                    @csrf
                                                    <input type="hidden" name="condominio_id" value="{{ $condominio->id }}">
                                                    <button type="submit" class="btn btn-sm {{ $condominio->pivot->ativo ? 'btn-success' : 'btn-outline-secondary' }}"
                                                            title="{{ $condominio->pivot->ativo ? 'Desativar Síndico' : 'Ativar Síndico' }}">
                                                        {{ $condominio->nome }}
                                                        @if($condominio->pivot->ativo)
                                                        <i class="fas fa-check-circle ms-1"></i>
                                                        @endif
                                                    </button>
                                                </form>
                                                
                                                <form method="POST" action="{{ route('sindicos.desvincular', $sindico) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="condominio_id" value="{{ $condominio->id }}">
                                                   <button type="submit" class="btn btn-sm btn-outline-danger px- py-2" 
                                                            title="Desvincular Condomínio"
                                                            onclick="return confirm('Tem certeza que deseja desvincular este síndico do condomínio?')">
                                                        <i class="fas fa-times" style="width: 1rem; text-align: center;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        @empty
                                        <span class="text-muted">Nenhum condomínio vinculado</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('sindicos.vincular', $sindico) }}" class="row g-2">
                                            @csrf
                                            <div class="col-8">
                                                <select name="condominio_id" class="form-select form-select-sm" required>
                                                    <option value="">Selecione um condomínio...</option>
                                                    @foreach($condominios as $condominio)
                                                        @if(!$sindico->condominios->contains('id', $condominio->id))
                                                            <option value="{{ $condominio->id }}">
                                                                {{ $condominio->nome }}
                                                                @if($condominio->sindicoAtivo())
                                                                    (Síndico ativo: {{ $condominio->sindicoAtivo()->name }})
                                                                @endif
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <button type="submit" class="btn btn-sm btn-primary w-100" title="Vincular Condomínio">
                                                    <i class="fas fa-link"></i> Vincular
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="{{ route('users.edit', $sindico) }}" class="btn btn-sm btn-outline-primary" title="Editar Usuário">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <form action="{{ route('users.destroy', $sindico) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        title="Excluir Usuário"
                                                        onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                    <i class="fas fa-trash-alt"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Nenhum síndico cadastrado
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Administradores -->
        <div class="tab-pane fade" id="admins" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Administrador</th>
                                    <th>Último Acesso</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($admins as $admin)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-user-cog fa-lg text-secondary me-2"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $admin->name }}</h6>
                                                <small class="text-muted">{{ $admin->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $admin->last_login_at ? $admin->last_login_at->format('d/m/Y H:i') : 'Nunca acessou' }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('users.edit', $admin) }}" class="btn btn-sm btn-outline-primary" title="Editar Usuário">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            @if($admin->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $admin) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                        title="Excluir Usuário"
                                                        onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                    <i class="fas fa-trash-alt"></i> Excluir
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        Nenhum administrador cadastrado
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .nav-tabs .nav-link {
        font-weight: 500;
    }
    .table th {
        font-weight: 500;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge {
        font-size: 0.65em;
        vertical-align: middle;
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
        $('[title]').tooltip()
    })
</script>
@endpush