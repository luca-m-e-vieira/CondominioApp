@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Criar Novo Condomínio') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('condominios.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="nome" class="form-label">{{ __('Nome do Condomínio') }}</label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome') }}" required autofocus>
                            
                            @error('nome')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="endereco" class="form-label">{{ __('Endereço') }}</label>
                            <textarea class="form-control @error('endereco') is-invalid @enderror" id="endereco" name="endereco" rows="3" required>{{ old('endereco') }}</textarea>
                            
                            @error('endereco')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Salvar Condomínio') }}
                            </button>
                            <a href="{{ route('condominios.index') }}" class="btn btn-secondary">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection