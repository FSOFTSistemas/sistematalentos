@extends('adminlte::page')

@section('title', 'Gerenciamento de Despesas')

@section('breadcrumb')
    <li class="breadcrumb-item active">Despesas</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Controle de Despesas</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-nova-despesa">
                    <i class="fas fa-plus"></i> Nova Despesa
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total de Despesas (Mês Atual)</span>
                            <span class="info-box-number">R$ {{ number_format($totalDespesasMesAtual ?? 0, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Despesas Pendentes</span>
                            <span class="info-box-number">R$ {{ number_format($totalDespesasPendentes ?? 0, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Despesas Pagas</span>
                            <span class="info-box-number">R$ {{ number_format($totalDespesasPagas ?? 0, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th>Vencimento</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($despesas ?? [] as $despesa)
                        <tr>
                            <td>{{ $despesa->id }}</td>
                            <td>{{ $despesa->descricao }}</td>
                            <td>R$ {{ number_format($despesa->valor, 2, ',', '.') }}</td>
                            <td>{{ $despesa->data ? $despesa->data->format('d/m/Y') : 'Não informado' }}</td>
                            <td>{{ $despesa->data_vencimento ? $despesa->data_vencimento->format('d/m/Y') : 'Não informado' }}</td>
                            <td>{{ $despesa->categoria }}</td>
                            <td>
                                @if($despesa->status == 'pago')
                                    <span class="badge badge-success">Pago</span>
                                @elseif($despesa->status == 'pendente')
                                    <span class="badge badge-warning">Pendente</span>
                                @elseif($despesa->status == 'vencido')
                                    <span class="badge badge-danger">Vencido</span>
                                @else
                                    <span class="badge badge-secondary">{{ $despesa->status }}</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-view-{{ $despesa->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-edit-{{ $despesa->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('despesas.destroy', $despesa->id) }}" method="POST" style="display: inline;">
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

    <!-- Modal para Nova Despesa -->
    <div class="modal fade" id="modal-nova-despesa">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Nova Despesa</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('despesas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>
                        <div class="form-group">
                            <label for="valor">Valor</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">R$</span>
                                </div>
                                <input type="number" step="0.01" min="0.01" class="form-control" id="valor" name="valor" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data">Data de Registro</label>
                                    <input type="date" class="form-control" id="data" name="data" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data_vencimento">Data de Vencimento</label>
                                    <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria">Categoria</label>
                                    <select class="form-control" id="categoria" name="categoria" required>
                                        <option value="">Selecione uma categoria</option>
                                        <option value="Água/Luz">Água/Luz</option>
                                        <option value="Aluguel">Aluguel</option>
                                        <option value="Material">Material</option>
                                        <option value="Manutenção">Manutenção</option>
                                        <option value="Salário">Salário</option>
                                        <option value="Evento">Evento</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="pendente">Pendente</option>
                                        <option value="pago">Pago</option>
                                        <option value="vencido">Vencido</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fornecedor">Fornecedor</label>
                            <input type="text" class="form-control" id="fornecedor" name="fornecedor">
                        </div>
                        <div class="form-group">
                            <label for="numero_documento">Número do Documento</label>
                            <input type="text" class="form-control" id="numero_documento" name="numero_documento">
                        </div>
                        <div class="form-group">
                            <label for="observacao">Observação</label>
                            <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
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
    @foreach($despesas ?? [] as $despesa)
        <!-- Modal de Visualização -->
        <div class="modal fade" id="modal-view-{{ $despesa->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Detalhes da Despesa</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <dl class="row">
                            <dt class="col-sm-4">ID:</dt>
                            <dd class="col-sm-8">{{ $despesa->id }}</dd>
                            
                            <dt class="col-sm-4">Descrição:</dt>
                            <dd class="col-sm-8">{{ $despesa->descricao }}</dd>
                            
                            <dt class="col-sm-4">Valor:</dt>
                            <dd class="col-sm-8">R$ {{ number_format($despesa->valor, 2, ',', '.') }}</dd>
                            
                            <dt class="col-sm-4">Data:</dt>
                            <dd class="col-sm-8">{{ $despesa->data ? $despesa->data->format('d/m/Y') : 'Não informado' }}</dd>
                            
                            <dt class="col-sm-4">Vencimento:</dt>
                            <dd class="col-sm-8">{{ $despesa->data_vencimento ? $despesa->data_vencimento->format('d/m/Y') : 'Não informado' }}</dd>
                            
                            <dt class="col-sm-4">Categoria:</dt>
                            <dd class="col-sm-8">{{ $despesa->categoria }}</dd>
                            
                            <dt class="col-sm-4">Status:</dt>
                            <dd class="col-sm-8">
                                @if($despesa->status == 'pago')
                                    <span class="badge badge-success">Pago</span>
                                @elseif($despesa->status == 'pendente')
                                    <span class="badge badge-warning">Pendente</span>
                                @elseif($despesa->status == 'vencido')
                                    <span class="badge badge-danger">Vencido</span>
                                @else
                                    <span class="badge badge-secondary">{{ $despesa->status }}</span>
                                @endif
                            </dd>
                            
                            <dt class="col-sm-4">Fornecedor:</dt>
                            <dd class="col-sm-8">{{ $despesa->fornecedor ?? 'Não informado' }}</dd>
                            
                            <dt class="col-sm-4">Documento:</dt>
                            <dd class="col-sm-8">{{ $despesa->numero_documento ?? 'Não informado' }}</dd>
                            
                            <dt class="col-sm-4">Observação:</dt>
                            <dd class="col-sm-8">{{ $despesa->observacao ?? 'Nenhuma observação' }}</dd>
                            
                            <dt class="col-sm-4">Registrado por:</dt>
                            <dd class="col-sm-8">{{ $despesa->user->name ?? 'Sistema' }}</dd>
                            
                            <dt class="col-sm-4">Data de Registro:</dt>
                            <dd class="col-sm-8">{{ $despesa->created_at->format('d/m/Y H:i:s') }}</dd>
                        </dl>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal-edit-{{ $despesa->id }}">Editar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="modal-edit-{{ $despesa->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Editar Despesa</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('despesas.update', $despesa->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <input type="text" class="form-control" id="descricao" name="descricao" value="{{ $despesa->descricao }}" required>
                            </div>
                            <div class="form-group">
                                <label for="valor">Valor</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="number" step="0.01" min="0.01" class="form-control" id="valor" name="valor" value="{{ $despesa->valor }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="data">Data de Registro</label>
                                        <input type="date" class="form-control" id="data" name="data" value="{{ $despesa->data ? $despesa->data->format('Y-m-d') : date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="data_vencimento">Data de Vencimento</label>
                                        <input type="date" class="form-control" id="data_vencimento" name="data_vencimento" value="{{ $despesa->data_vencimento ? $despesa->data_vencimento->format('Y-m-d') : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="categoria">Categoria</label>
                                        <select class="form-control" id="categoria" name="categoria" required>
                                            <option value="">Selecione uma categoria</option>
                                            <option value="Água/Luz" {{ $despesa->categoria == 'Água/Luz' ? 'selected' : '' }}>Água/Luz</option>
                                            <option value="Aluguel" {{ $despesa->categoria == 'Aluguel' ? 'selected' : '' }}>Aluguel</option>
                                            <option value="Material" {{ $despesa->categoria == 'Material' ? 'selected' : '' }}>Material</option>
                                            <option value="Manutenção" {{ $despesa->categoria == 'Manutenção' ? 'selected' : '' }}>Manutenção</option>
                                            <option value="Salário" {{ $despesa->categoria == 'Salário' ? 'selected' : '' }}>Salário</option>
                                            <option value="Evento" {{ $despesa->categoria == 'Evento' ? 'selected' : '' }}>Evento</option>
                                            <option value="Outro" {{ $despesa->categoria == 'Outro' ? 'selected' : '' }}>Outro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status" required>
                                            <option value="pendente" {{ $despesa->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                            <option value="pago" {{ $despesa->status == 'pago' ? 'selected' : '' }}>Pago</option>
                                            <option value="vencido" {{ $despesa->status == 'vencido' ? 'selected' : '' }}>Vencido</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fornecedor">Fornecedor</label>
                                <input type="text" class="form-control" id="fornecedor" name="fornecedor" value="{{ $despesa->fornecedor }}">
                            </div>
                            <div class="form-group">
                                <label for="numero_documento">Número do Documento</label>
                                <input type="text" class="form-control" id="numero_documento" name="numero_documento" value="{{ $despesa->numero_documento }}">
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <textarea class="form-control" id="observacao" name="observacao" rows="3">{{ $despesa->observacao }}</textarea>
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
        // Inicialização específica para a tabela de despesas
        $('.datatable').DataTable({
            "order": [[4, 'asc']], // Ordenar por data de vencimento (coluna 4) crescente
            "buttons": [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }
            ],
            "dom": 'Bfrtip'
        });
        
        // Verificar vencimentos e atualizar status
        $('#status').on('change', function() {
            if ($(this).val() === 'pago') {
                $('#data').val('{{ date('Y-m-d') }}');
            }
        });
    });
</script>
@endsection
