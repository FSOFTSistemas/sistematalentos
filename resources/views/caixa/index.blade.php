@extends('adminlte::page')

@section('title', 'Gerenciamento de Caixa')

@section('breadcrumb')
    <li class="breadcrumb-item active">Caixa</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Movimentações do Caixa</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-entrada">
                    <i class="fas fa-plus-circle"></i> Nova Entrada
                </button>
                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-saida">
                    <i class="fas fa-minus-circle"></i> Nova Saída
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total de Entradas</span>
                            <span class="info-box-number">R$ {{ number_format($totalEntradas ?? 0, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total de Saídas</span>
                            <span class="info-box-number">R$ {{ number_format($totalSaidas ?? 0, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Saldo Atual</span>
                            <span class="info-box-number">R$ {{ number_format(($totalEntradas ?? 0) - ($totalSaidas ?? 0), 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimentacoes ?? [] as $movimentacao)
                        <tr>
                            <td>{{ $movimentacao->id }}</td>
                            <td>{{ $movimentacao->data->format('d/m/Y') }}</td>
                            <td>{{ $movimentacao->descricao }}</td>
                            <td>{{ $movimentacao->categoria }}</td>
                            <td>
                                @if($movimentacao->tipo == 'entrada')
                                    <span class="badge badge-success">Entrada</span>
                                @else
                                    <span class="badge badge-danger">Saída</span>
                                @endif
                            </td>
                            <td>R$ {{ number_format($movimentacao->valor, 2, ',', '.') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-view-{{ $movimentacao->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-edit-{{ $movimentacao->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('caixa.destroy', $movimentacao->id) }}" method="POST" style="display: inline;">
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

    <!-- Modal para Nova Entrada -->
    <div class="modal fade" id="modal-entrada">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h4 class="modal-title">Nova Entrada</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('caixa.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tipo" value="entrada">
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
                        <div class="form-group">
                            <label for="data">Data</label>
                            <input type="date" class="form-control" id="data" name="data" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="categoria">Categoria</label>
                            <select class="form-control" id="categoria" name="categoria" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="Dízimo">Dízimo</option>
                                <option value="Oferta">Oferta</option>
                                <option value="Doação">Doação</option>
                                <option value="Evento">Evento</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="observacao">Observação</label>
                            <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Nova Saída -->
    <div class="modal fade" id="modal-saida">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Nova Saída</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('caixa.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tipo" value="saida">
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
                        <div class="form-group">
                            <label for="data">Data</label>
                            <input type="date" class="form-control" id="data" name="data" value="{{ date('Y-m-d') }}" required>
                        </div>
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
                        <div class="form-group">
                            <label for="observacao">Observação</label>
                            <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modais de Edição e Visualização seriam gerados dinamicamente para cada item -->
    @foreach($movimentacoes ?? [] as $movimentacao)
        <!-- Modal de Visualização -->
        <div class="modal fade" id="modal-view-{{ $movimentacao->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header {{ $movimentacao->tipo == 'entrada' ? 'bg-success' : 'bg-danger' }}">
                        <h4 class="modal-title">Detalhes da {{ $movimentacao->tipo == 'entrada' ? 'Entrada' : 'Saída' }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <dl class="row">
                            <dt class="col-sm-4">ID:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->id }}</dd>
                            
                            <dt class="col-sm-4">Descrição:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->descricao }}</dd>
                            
                            <dt class="col-sm-4">Valor:</dt>
                            <dd class="col-sm-8">R$ {{ number_format($movimentacao->valor, 2, ',', '.') }}</dd>
                            
                            <dt class="col-sm-4">Data:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->data->format('d/m/Y') }}</dd>
                            
                            <dt class="col-sm-4">Categoria:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->categoria }}</dd>
                            
                            <dt class="col-sm-4">Tipo:</dt>
                            <dd class="col-sm-8">
                                @if($movimentacao->tipo == 'entrada')
                                    <span class="badge badge-success">Entrada</span>
                                @else
                                    <span class="badge badge-danger">Saída</span>
                                @endif
                            </dd>
                            
                            <dt class="col-sm-4">Observação:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->observacao ?? 'Nenhuma observação' }}</dd>
                            
                            <dt class="col-sm-4">Registrado por:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->user->name ?? 'Sistema' }}</dd>
                            
                            <dt class="col-sm-4">Data de Registro:</dt>
                            <dd class="col-sm-8">{{ $movimentacao->created_at->format('d/m/Y H:i:s') }}</dd>
                        </dl>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="modal-edit-{{ $movimentacao->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header {{ $movimentacao->tipo == 'entrada' ? 'bg-success' : 'bg-danger' }}">
                        <h4 class="modal-title">Editar {{ $movimentacao->tipo == 'entrada' ? 'Entrada' : 'Saída' }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('caixa.update', $movimentacao->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tipo" value="{{ $movimentacao->tipo }}">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <input type="text" class="form-control" id="descricao" name="descricao" value="{{ $movimentacao->descricao }}" required>
                            </div>
                            <div class="form-group">
                                <label for="valor">Valor</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="number" step="0.01" min="0.01" class="form-control" id="valor" name="valor" value="{{ $movimentacao->valor }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="data">Data</label>
                                <input type="date" class="form-control" id="data" name="data" value="{{ $movimentacao->data->format('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="categoria">Categoria</label>
                                <select class="form-control" id="categoria" name="categoria" required>
                                    @if($movimentacao->tipo == 'entrada')
                                        <option value="Dízimo" {{ $movimentacao->categoria == 'Dízimo' ? 'selected' : '' }}>Dízimo</option>
                                        <option value="Oferta" {{ $movimentacao->categoria == 'Oferta' ? 'selected' : '' }}>Oferta</option>
                                        <option value="Doação" {{ $movimentacao->categoria == 'Doação' ? 'selected' : '' }}>Doação</option>
                                        <option value="Evento" {{ $movimentacao->categoria == 'Evento' ? 'selected' : '' }}>Evento</option>
                                        <option value="Outro" {{ $movimentacao->categoria == 'Outro' ? 'selected' : '' }}>Outro</option>
                                    @else
                                        <option value="Água/Luz" {{ $movimentacao->categoria == 'Água/Luz' ? 'selected' : '' }}>Água/Luz</option>
                                        <option value="Aluguel" {{ $movimentacao->categoria == 'Aluguel' ? 'selected' : '' }}>Aluguel</option>
                                        <option value="Material" {{ $movimentacao->categoria == 'Material' ? 'selected' : '' }}>Material</option>
                                        <option value="Manutenção" {{ $movimentacao->categoria == 'Manutenção' ? 'selected' : '' }}>Manutenção</option>
                                        <option value="Salário" {{ $movimentacao->categoria == 'Salário' ? 'selected' : '' }}>Salário</option>
                                        <option value="Evento" {{ $movimentacao->categoria == 'Evento' ? 'selected' : '' }}>Evento</option>
                                        <option value="Outro" {{ $movimentacao->categoria == 'Outro' ? 'selected' : '' }}>Outro</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <textarea class="form-control" id="observacao" name="observacao" rows="3">{{ $movimentacao->observacao }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn {{ $movimentacao->tipo == 'entrada' ? 'btn-success' : 'btn-danger' }}">Atualizar</button>
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
        // Inicialização específica para a tabela de caixa
        $('.datatable').DataTable({
            "order": [[1, 'desc']], // Ordenar por data (coluna 1) decrescente
            "buttons": [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Imprimir',
                    className: 'btn btn-info btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                }
            ],
            "dom": 'Bfrtip'
        });
    });
</script>
@endsection
