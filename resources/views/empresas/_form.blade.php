@extends('adminlte::page')

@section('title', isset($empresa) ? 'Editar Empresa' : 'Nova Empresa')

@section('content_header')
    <h1>{{ isset($empresa) ? 'Editar Empresa' : 'Nova Empresa' }}</h1>
    <hr>
@stop

@section('content')
    <form action="{{ isset($empresa) ? route('empresas.update', $empresa->id) : route('empresas.store') }}" method="POST">
        @csrf
        @if (isset($empresa))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <h5>Dados da Empresa</h5>
                <div class="form-group">
                    <label for="nome">Nome da Empresa/Igreja</label>
                    <input type="text" class="form-control" id="nome" name="nome"
                        value="{{ old('nome', $empresa->nome ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label for="cnpj">CNPJ</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="cnpj" name="cnpj"
                            value="{{ old('cnpj', $empresa->cnpj ?? '') }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="btnBuscarCnpj">
                                Buscar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ old('email', $empresa->email ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone"
                        value="{{ old('telefone', $empresa->telefone ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="responsavel">Responsável</label>
                    <input type="text" class="form-control" id="responsavel" name="responsavel"
                        value="{{ old('responsavel', $empresa->responsavel ?? '') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <h5>Dados do Plano</h5>
                <div class="form-group">
                    <label for="plano_id">Plano</label>
                    <select class="form-control" id="plano_id" name="plano_id" required>
                        <option value="">Selecione um plano</option>
                        @foreach ($planos ?? [] as $plano)
                            <option value="{{ $plano->id }}"
                                {{ old('plano_id', $empresa->plano_id ?? '') == $plano->id ? 'selected' : '' }}>
                                {{ $plano->nome }} - R$ {{ number_format($plano->valor, 2, ',', '.') }}
                                ({{ $plano->periodoFormatado }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="data_inicio_plano">Data de Início</label>
                    <input type="date" class="form-control" id="data_inicio_plano" name="data_inicio_plano"
                        value="{{ old('data_inicio_plano', isset($empresa) ? $empresa->data_inicio_plano->format('Y-m-d') : date('Y-m-d')) }}"
                        required>
                </div>
                <div class="form-group">
                    <label for="observacoes">Observações</label>
                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3">{{ old('observacoes', $empresa->observacoes ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <hr>

        @if (!isset($empresa))
            <div class="row">
                <div class="col-md-12">
                    <h5>Dados do Administrador</h5>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Estas informações serão usadas para criar o usuário administrador
                        da empresa.
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="admin_name">Nome</label>
                        <input type="text" class="form-control" id="admin_name" name="admin_name" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="admin_email">Email</label>
                        <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="admin_password">Senha</label>
                        <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                    </div>
                </div>
            </div>
        @endif

        <div class="card-footer">
                <div class="row w-100">
                    <div class="col-md-6">
                        <a href="{{ route('empresas.index') }}" class="btn btn-secondary btn-block shadow">
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
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#cnpj').inputmask('99.999.999/9999-99');
            $('#telefone').inputmask('(99) 99999-9999');
        });
    </script>

    <script>
        document.getElementById('btnBuscarCnpj').addEventListener('click', function() {
            const cnpj = document.getElementById('cnpj').value.replace(/\D/g, '');

            if (cnpj.length !== 14) {
                alert('CNPJ inválido. Deve conter 14 dígitos.');
                return;
            }

            fetch(`https://open.cnpja.com/office/${cnpj}`)
                .then(response => {
                    if (!response.ok) throw new Error('Erro ao buscar CNPJ.');
                    return response.json();
                })
                .then(data => {
                    const razao = data.company?.name || '';
                    const email = data.emails?.[0]?.address || '';
                    const telefone = data.phones?.[0] ? data.phones[0].area + data.phones[0].number.replace(/\D/g, '') : '';

                    document.getElementById('nome').value = razao;
                    document.getElementById('email').value = email;
                    document.getElementById('telefone').value = telefone;
                })
                .catch(error => {
                    console.error(error);
                    alert('Erro ao buscar CNPJ. Verifique o console.');
                });
        });
    </script>

@stop
