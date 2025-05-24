@extends('adminlte::page')

@section('title', isset($membro) ? 'Editar Membro' : 'Novo Membro')

@section('content_header')
    <h1 class="m-0 text-dark">{{ isset($membro) ? 'Editar Membro' : 'Novo Membro' }}</h1>
@stop

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ isset($membro) ? 'Atualizar dados do membro' : 'Cadastro de novo membro' }}</h3>
        </div>

        <form action="{{ isset($membro) ? route('membros.update', $membro->id) : route('membros.store') }}" method="POST">
            @csrf
            @if (isset($membro))
                @method('PUT')
            @endif

            <div class="card-body">

                {{-- Informações Pessoais --}}
                <div class="border rounded p-3 mb-4 bg-light">
                    <h5 class="mb-3">Informações Pessoais</h5>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="nome">Nome Completo</label>
                            <input type="text" name="nome" class="form-control"
                                value="{{ old('nome', $membro->nome ?? '') }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cpf">CPF</label>
                            <input type="text" name="cpf" class="form-control" placeholder="000.000.000-00"
                                maxlength="14" value="{{ old('cpf', $membro->cpf ?? '') }}">
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="data_nascimento">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" class="form-control"
                            value="{{ old('data_nascimento', isset($membro->data_nascimento) ? $membro->data_nascimento->format('Y-m-d') : '') }}">
                    </div>


                    {{-- Contatos --}}
                    <h5 class="mb-3 mt-4">Contatos</h5>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $membro->email ?? '') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="telefone">Telefone</label>
                            <input type="text" name="telefone" class="form-control" placeholder="(00) 00000-0000"
                                maxlength="15" value="{{ old('telefone', $membro->telefone ?? '') }}">
                        </div>
                    </div>
                </div>
                {{-- Informações de Membro --}}
                <div class="border rounded p-3 mb-4 bg-light">
                    <h5 class="mb-3 mt-4">Informações de Membro</h5>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="data_batismo">Data de Batismo</label>
                            <input type="date" name="data_batismo" class="form-control"
                                value="{{ old('data_batismo', isset($membro->data_batismo) ? $membro->data_batismo->format('Y-m-d') : '') }}">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="data_admissao">Data de Admissão</label>
                            <input type="date" name="data_admissao" class="form-control"
                                value="{{ old('data_admissao', isset($membro->data_admissao) ? $membro->data_admissao->format('Y-m-d') : '') }}">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="status">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="ativo" @selected(old('status', $membro->status ?? '') == 'ativo')>Ativo</option>
                                <option value="inativo" @selected(old('status', $membro->status ?? '') == 'inativo')>Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>
                {{-- Endereço --}}
                <div class="border rounded p-3 mb-4 bg-light">
                    <h5 class="mb-3 mt-4">Endereço</h5>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="endereco">Endereço</label>
                            <input type="text" name="endereco" class="form-control"
                                value="{{ old('endereco', $membro->endereco ?? '') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="bairro">Bairro</label>
                            <input type="text" name="bairro" class="form-control"
                                value="{{ old('bairro', $membro->bairro ?? '') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="cidade">Cidade</label>
                            <input type="text" name="cidade" class="form-control"
                                value="{{ old('cidade', $membro->cidade ?? '') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="estado">Estado</label>
                            <select name="estado" class="form-control">
                                <option value="">Selecione</option>
                                @foreach (['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $uf)
                                    <option value="{{ $uf }}" @selected(old('estado', $membro->estado ?? '') == $uf)>{{ $uf }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="cep">CEP</label>
                            <input type="text" name="cep" class="form-control" placeholder="00000-000"
                                value="{{ old('cep', $membro->cep ?? '') }}">
                        </div>
                    </div>
                </div>
                {{-- Observações --}}
                <h5 class="mb-3 mt-4">Observações</h5>
                <div class="form-group">
                    <textarea name="observacoes" class="form-control" rows="3">{{ old('observacoes', $membro->observacoes ?? '') }}</textarea>
                </div>
            </div>

            <div class="card-footer">
                <div class="row w-100">
                    <div class="col-md-6">
                        <a href="{{ route('membros.index') }}" class="btn btn-secondary btn-block shadow">
                            <i class="fas fa-arrow-left mr-1"></i> Cancelar
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary btn-block shadow">
                            <i class="fas fa-save mr-1"></i> {{ isset($membro) ? 'Atualizar' : 'Salvar' }}
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
        $(document).ready(function() {
            $('input[name="cpf"]').mask('000.000.000-00');
            $('input[name="telefone"]').mask('(00) 00000-0000');
            $('input[name="cep"]').mask('00000-000');
        });
    </script>
@endsection
