@extends('adminlte::page')

@section('title', 'Gerenciamento de Membros')

@section('breadcrumb')
    <li class="breadcrumb-item active">Membros</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Membros</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-novo-membro">
                    <i class="fas fa-plus"></i> Novo Membro
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($membros ?? [] as $membro)
                        <tr>
                            <td>{{ $membro->id }}</td>
                            <td>{{ $membro->nome }}</td>
                            <td>{{ $membro->telefone ?? 'Não informado' }}</td>
                            <td>{{ $membro->email ?? 'Não informado' }}</td>
                            <td>
                                @if($membro->status == 'ativo')
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-secondary">Inativo</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-view-{{ $membro->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-edit-{{ $membro->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('membros.destroy', $membro->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para Novo Membro -->
    <div class="modal fade" id="modal-novo-membro">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Novo Membro</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('membros.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nome">Nome Completo</label>
                                    <input type="text" class="form-control" id="nome" name="nome" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cpf">CPF</label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefone">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="data_nascimento">Data de Nascimento</label>
                                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="data_batismo">Data de Batismo</label>
                                    <input type="date" class="form-control" id="data_batismo" name="data_batismo">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="data_admissao">Data de Admissão</label>
                                    <input type="date" class="form-control" id="data_admissao" name="data_admissao">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="endereco">Endereço</label>
                                    <input type="text" class="form-control" id="endereco" name="endereco">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bairro">Bairro</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cidade">Cidade</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <select class="form-control" id="estado" name="estado">
                                        <option value="">Selecione</option>
                                        <option value="AC">AC</option>
                                        <option value="AL">AL</option>
                                        <option value="AP">AP</option>
                                        <option value="AM">AM</option>
                                        <option value="BA">BA</option>
                                        <option value="CE">CE</option>
                                        <option value="DF">DF</option>
                                        <option value="ES">ES</option>
                                        <option value="GO">GO</option>
                                        <option value="MA">MA</option>
                                        <option value="MT">MT</option>
                                        <option value="MS">MS</option>
                                        <option value="MG">MG</option>
                                        <option value="PA">PA</option>
                                        <option value="PB">PB</option>
                                        <option value="PR">PR</option>
                                        <option value="PE">PE</option>
                                        <option value="PI">PI</option>
                                        <option value="RJ">RJ</option>
                                        <option value="RN">RN</option>
                                        <option value="RS">RS</option>
                                        <option value="RO">RO</option>
                                        <option value="RR">RR</option>
                                        <option value="SC">SC</option>
                                        <option value="SP">SP</option>
                                        <option value="SE">SE</option>
                                        <option value="TO">TO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cep">CEP</label>
                                    <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="ativo">Ativo</option>
                                        <option value="inativo">Inativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="observacoes">Observações</label>
                                    <textarea class="form-control" id="observacoes" name="observacoes" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modais de Edição e Visualização seriam gerados dinamicamente para cada item -->
    @foreach($membros ?? [] as $membro)
        <!-- Modal de Visualização -->
        <div class="modal fade" id="modal-view-{{ $membro->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Detalhes do Membro</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 text-center mb-4">
                                <h3>{{ $membro->nome }}</h3>
                                <p>
                                    @if($membro->status == 'ativo')
                                        <span class="badge badge-success">Ativo</span>
                                    @else
                                        <span class="badge badge-secondary">Inativo</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-4">ID:</dt>
                                    <dd class="col-sm-8">{{ $membro->id }}</dd>
                                    
                                    <dt class="col-sm-4">CPF:</dt>
                                    <dd class="col-sm-8">{{ $membro->cpf ?? 'Não informado' }}</dd>
                                    
                                    <dt class="col-sm-4">Email:</dt>
                                    <dd class="col-sm-8">{{ $membro->email ?? 'Não informado' }}</dd>
                                    
                                    <dt class="col-sm-4">Telefone:</dt>
                                    <dd class="col-sm-8">{{ $membro->telefone ?? 'Não informado' }}</dd>
                                    
                                    <dt class="col-sm-4">Nascimento:</dt>
                                    <dd class="col-sm-8">{{ $membro->data_nascimento ? $membro->data_nascimento->format('d/m/Y') : 'Não informado' }}</dd>
                                    
                                    <dt class="col-sm-4">Batismo:</dt>
                                    <dd class="col-sm-8">{{ $membro->data_batismo ? $membro->data_batismo->format('d/m/Y') : 'Não informado' }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-4">Admissão:</dt>
                                    <dd class="col-sm-8">{{ $membro->data_admissao ? $membro->data_admissao->format('d/m/Y') : 'Não informado' }}</dd>
                                    
                                    <dt class="col-sm-4">Endereço:</dt>
                                    <dd class="col-sm-8">{{ $membro->endereco ?? 'Não informado' }}</dd>
                                    
                                    <dt class="col-sm-4">Bairro:</dt>
                                    <dd class="col-sm-8">{{ $membro->bairro ?? 'Não informado' }}</dd>
                                    
                                    <dt class="col-sm-4">Cidade/UF:</dt>
                                    <dd class="col-sm-8">{{ ($membro->cidade ?? 'Não informado') . '/' . ($membro->estado ?? '') }}</dd>
                                    
                                    <dt class="col-sm-4">CEP:</dt>
                                    <dd class="col-sm-8">{{ $membro->cep ?? 'Não informado' }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5>Observações:</h5>
                                <p>{{ $membro->observacoes ?? 'Nenhuma observação registrada.' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal-edit-{{ $membro->id }}">Editar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="modal-edit-{{ $membro->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Editar Membro</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('membros.update', $membro->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nome">Nome Completo</label>
                                        <input type="text" class="form-control" id="nome" name="nome" value="{{ $membro->nome }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpf">CPF</label>
                                        <input type="text" class="form-control" id="cpf" name="cpf" value="{{ $membro->cpf }}" placeholder="000.000.000-00">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $membro->email }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefone">Telefone</label>
                                        <input type="text" class="form-control" id="telefone" name="telefone" value="{{ $membro->telefone }}" placeholder="(00) 00000-0000">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="data_nascimento">Data de Nascimento</label>
                                        <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="{{ $membro->data_nascimento ? $membro->data_nascimento->format('Y-m-d') : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="data_batismo">Data de Batismo</label>
                                        <input type="date" class="form-control" id="data_batismo" name="data_batismo" value="{{ $membro->data_batismo ? $membro->data_batismo->format('Y-m-d') : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="data_admissao">Data de Admissão</label>
                                        <input type="date" class="form-control" id="data_admissao" name="data_admissao" value="{{ $membro->data_admissao ? $membro->data_admissao->format('Y-m-d') : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="endereco">Endereço</label>
                                        <input type="text" class="form-control" id="endereco" name="endereco" value="{{ $membro->endereco }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bairro">Bairro</label>
                                        <input type="text" class="form-control" id="bairro" name="bairro" value="{{ $membro->bairro }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cidade">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" value="{{ $membro->cidade }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        <select class="form-control" id="estado" name="estado">
                                            <option value="">Selecione</option>
                                            @foreach(['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'] as $uf)
                                                <option value="{{ $uf }}" {{ $membro->estado == $uf ? 'selected' : '' }}>{{ $uf }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cep">CEP</label>
                                        <input type="text" class="form-control" id="cep" name="cep" value="{{ $membro->cep }}" placeholder="00000-000">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="ativo" {{ $membro->status == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                            <option value="inativo" {{ $membro->status == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="observacoes">Observações</label>
                                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3">{{ $membro->observacoes }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@section('scripts')
<script>
    $(function () {
        // Inicialização específica para a tabela de membros
        $('.datatable').DataTable({
            "buttons": [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
                    }
                }
            ],
            "dom": 'Bfrtip'
        });
        
        // Máscaras para campos
        if($.fn.inputmask) {
            $('#cpf').inputmask('999.999.999-99');
            $('#telefone').inputmask('(99) 99999-9999');
            $('#cep').inputmask('99999-999');
        }
    });
</script>
@endsection
