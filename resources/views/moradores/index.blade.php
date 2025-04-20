@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Moradores
            @can('create', App\Models\Morador::class)
                <a href="{{ route('moradores.create') }}" class="btn btn-primary float-end">Novo Morador</a>
            @endcan
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Condomínio</th>
                        <th>Apartamentos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
@forelse($moradores as $morador)
    <tr>
        <td>{{ $morador->nome }}</td>
        <td>{{ $morador->condominio->nome ?? 'N/A' }}</td>
        <td>{{ $morador->apartamentos->count() }}</td>
        <td>
            @can('update', $morador)
                <a href="{{ route('moradores.edit', $morador) }}" 
                   class="btn btn-sm btn-warning">Editar</a>
                
                @if($morador->condominio_id && !$morador->expulso)
                <form action="{{ route('moradores.expulsar', $morador) }}" 
                      method="POST" 
                      class="d-inline">
                    @csrf
                    <button type="submit" 
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Tem certeza que deseja expulsar este morador?')">
                        Expulsar
                    </button>
                </form>
                @endif
            @endcan
            
            @can('delete', $morador)
            <form action="{{ route('moradores.destroy', $morador) }}" 
                  method="POST" 
                  class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Excluir permanentemente?')">
                    Excluir
                </button>
            </form>
            @endcan
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center">Nenhum morador encontrado</td>
    </tr>
@endforelse
                </tbody>
            </table>

            @if($moradores instanceof \Illuminate\Pagination\AbstractPaginator)
                {{ $moradores->links() }}
            @endif
        </div>
    </div>
</div>
@endsection