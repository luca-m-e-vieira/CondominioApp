@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        {{-- Card Condomínios --}}
                        <div class="col-md-3 mb-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Condomínios</h5>
                                    <p class="card-text display-4">
                                        @if(auth()->user()->role === 'admin')
                                            {{ App\Models\Condominio::count() }}
                                        @else
                                            {{ auth()->user()->condominios()->wherePivot('ativo', true)->count() }}
                                        @endif
                                    </p>
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('condominios.index') }}" class="btn btn-light">Ver Todos</a>
                                    @else
                                        <span class="text-white">Seu Condomínio</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                            {{-- Card Usuários (NOVO) --}}
                        @if(auth()->user()->role === 'admin')
                        <div class="col-md-3 mb-3">
                            <div class="card text-white bg-info">
                                <div class="card-body">
                                    <h5 class="card-title">Usuários</h5>
                                    <p class="card-text display-4">{{ App\Models\User::count() }}</p>
                                    <a href="{{ route('users.index') }}" class="btn btn-light">Ver Todos</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Card Apartamentos --}}
                        <div class="col-md-3 mb-3">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h5 class="card-title">Apartamentos</h5>
                                    <p class="card-text display-4">
                                        @if(auth()->user()->role === 'admin')
                                            {{ App\Models\Apartamento::count() }}
                                        @else
                                            {{ App\Models\Apartamento::where('condominio_id', auth()->user()->condominios()->wherePivot('ativo', true)->first()->id ?? 0)->count() }}
                                        @endif
                                    </p>
                                    <a href="{{ route('apartamentos.index') }}" class="btn btn-light">Ver Todos</a>
                                </div>
                            </div>
                        </div>

                        {{-- Card Moradores --}}
                        <div class="col-md-3 mb-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body">
                                    <h5 class="card-title">Moradores</h5>
                                    <p class="card-text display-4">
                                        @if(auth()->user()->role === 'admin')
                                            {{ App\Models\Morador::count() }}
                                        @elseif(auth()->user()->role === 'sindico')
                                            {{ App\Models\Morador::where(
                                                'condominio_id', 
                                                auth()->user()->condominios()->wherePivot('ativo', true)->first()->id ?? 0
                                            )->count() }}
                                        @endif
                                    </p>
                                    <a href="{{ route('moradores.index') }}" class="btn btn-light">Ver Todos</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert @if(auth()->user()->role === 'admin')alert-info @elseif(auth()->user()->role === 'sindico')alert-warning @endif">
                        Você é um @if(auth()->user()->role === 'admin')Administrador @elseif(auth()->user()->role === 'sindico')Síndico @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection