@extends('adminlte::page')

@section('title', isset($patrimonio) ? 'Editar Patrimônio' : 'Novo Patrimônio')

@section('content_header')
    <h1>{{ isset($patrimonio) ? 'Editar Patrimônio' : 'Novo Patrimônio' }}</h1>
@stop

@section('content')
<form method="POST" action="{{ isset($patrimonio) ? route('patrimonios.update', $patrimonio->id) : route('patrimonios.store') }}">
    @csrf
    @if(isset($patrimonio))
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="border rounded p-3 mb-4 bg-light">
            <h5 class="mb-3">Dados do Patrimônio</h5>

            <div class="row mb-3">
                <div class="input-group">
                    <div class="form-group col-md-10">
                        <label for="categoria_id">Categoria</label>
                        <select name="categoria_id" id="categoria_id" class="form-control" required>
                            <option value="">Selecione</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" @selected(old('categoria_id', $patrimonio->categoria_id ?? '') == $categoria->id)>
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100" data-toggle="modal"
                            data-target="#modalNovaCategoria">
                            + Categoria
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="form-group col-md-6">
                    <label for="descricao">Descrição</label>
                    <input type="text" name="descricao" id="descricao" class="form-control"
                        value="{{ old('descricao', $patrimonio->descricao ?? '') }}" required>
                </div>

                <div class="form-group col-md-6">
                    <label for="numero_patrimonio">Número do Patrimônio</label>
                    <input type="number" name="numero_patrimonio" id="numero_patrimonio" class="form-control" min="0"
                        value="{{ old('numero_patrimonio', $patrimonio->numero_patrimonio ?? '') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="form-group col-md-3">
                    <label for="data_aquisicao">Data de Aquisição</label>
                    <input type="date" name="data_aquisicao" id="data_aquisicao" class="form-control"
                        value="{{ old('data_aquisicao', $patrimonio ? \Carbon\Carbon::parse($patrimonio->data_aquisicao)->format('Y-m-d') : '') }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="valor_aquisicao">Valor de Aquisição</label>
                    <input type="number" step="0.01" min="0" name="valor_aquisicao" id="valor_aquisicao" class="form-control"
                        value="{{ old('valor_aquisicao', $patrimonio->valor_aquisicao ?? '') }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="valor_atual">Valor Atual</label>
                    <input type="number" step="0.01" min="0" name="valor_atual" id="valor_atual" class="form-control"
                        value="{{ old('valor_atual', $patrimonio->valor_atual ?? '') }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="localizacao">Localização</label>
                    <input type="text" name="localizacao" id="localizacao" class="form-control"
                        value="{{ old('localizacao', $patrimonio->localizacao ?? '') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="form-group col-md-4">
                    <label for="responsavel">Responsável</label>
                    <input type="text" name="responsavel" id="responsavel" class="form-control"
                        value="{{ old('responsavel', $patrimonio->responsavel ?? '') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="estado_conservacao">Estado de Conservação</label>
                    <input type="text" name="estado_conservacao" id="estado_conservacao" class="form-control"
                        value="{{ old('estado_conservacao', $patrimonio->estado_conservacao ?? '') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="ativo">Ativo?</label>
                    <select name="ativo" id="ativo" class="form-control">
                        <option value="1" @selected(old('ativo', $patrimonio->ativo ?? 1) == 1)>Sim</option>
                        <option value="0" @selected(old('ativo', $patrimonio->ativo ?? 1) == 0)>Não</option>
                    </select>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="observacoes">Observações</label>
                <textarea name="observacoes" id="observacoes" class="form-control" rows="3">{{ old('observacoes', $patrimonio->observacoes ?? '') }}</textarea>
            </div>

            <div class="d-flex justify-content-center mt-4 gap-3" aria-label="Ações do formulário">
                <button type="submit" class="btn btn-success w-50 px-3">Salvar</button>
                <a href="{{ route('patrimonios.index') }}" class="btn btn-secondary w-50 px-3">Voltar</a>
            </div>
        </div>
    </div>
</form>

<!-- Modal Nova Categoria -->
<div class="modal fade" id="modalNovaCategoria" tabindex="-1" aria-labelledby="modalNovaCategoriaLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('categorias.store') }}" id="formNovaCategoria">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalNovaCategoriaLabel">Nova Categoria</h5>
                    <button type="button" class="btn-close text-white" data-dismiss="modal"
                        aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <label for="nome_categoria">Nome da Categoria</label>
                    <input type="text" name="nome" id="nome_categoria" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@stop
