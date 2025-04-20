@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Lista de Apartamentos
            @can('create', App\Models\Apartamento::class)
                <a href="{{ route('apartamentos.create') }}" class="btn btn-primary float-end">Novo Apartamento</a>
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
                        <th>Número</th>
                        <th>Condomínio</th>
                        <th>Morador</th>
                        @can('admin')
                        <th>Ações</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse($apartamentos as $apartamento)
                        <tr>
                            <td>{{ $apartamento->numero }}</td>
                            <td>{{ $apartamento->condominio->nome }}</td>
                                <td>
                                    @if($apartamento->morador)
                                        {{ $apartamento->morador->nome }}
                                    @else
                                        <span class="text-muted">Vago</span>
                                    @endif
                                </td>
                            @canany(['update', 'delete'], $apartamento)
                            <td>
                                @can('update', $apartamento)
                                <a href="{{ route('apartamentos.edit', $apartamento) }}" 
                                   class="btn btn-sm btn-warning">Editar</a>
                                @endcan
                                
                                @can('delete', $apartamento)
            <form action="{{ route('apartamentos.destroy', $apartamento) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" 
                        onclick="return confirm('Tem certeza que deseja excluir este apartamento?')">
                    Excluir
                </button>
            </form>
                                @endcan
                            </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Nenhum apartamento encontrado</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($apartamentos instanceof \Illuminate\Pagination\AbstractPaginator)
                {{ $apartamentos->links() }}
            @endif
        </div>
    </div>
</div>
@endsection