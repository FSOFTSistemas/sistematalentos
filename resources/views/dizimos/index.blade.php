@extends('adminlte::page')

@section('title', 'Gerenciamento de Dízimos')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dízimos</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Registro de Dízimos</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-novo-dizimo">
                    <i class="fas fa-plus"></i> Novo Dízimo
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-hand-holding-usd"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total de Dízimos (Mês Atual)</span>
                            <span class="info-box-number">R$ {{ number_format($totalDizimosMesAtual ?? 0, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total de Dizimistas (Mês Atual)</span>
                            <span class="info-box-number">{{ $totalDizimistasMesAtual ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Média por Dizimista</span>
                            <span class="info-box-number">R$ {{ $totalDizimistasMesAtual > 0 ? number_format(($totalDizimosMesAtual ?? 0) / ($totalDizimistasMesAtual ?? 1), 2, ',', '.') : '0,00' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped datatable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Membro</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th>Mês/Ano Ref.</th>
                        <th>Registrado por</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dizimos ?? [] as $dizimo)
                        <tr>
                            <td>{{ $dizimo->id }}</td>
                            <td>{{ $dizimo->membro->nome ?? 'Não informado' }}</td>
                            <td>R$ {{ number_format($dizimo->valor, 2, ',', '.') }}</td>
                            <td>{{ $dizimo->data->format('d/m/Y') }}</td>
                            <td>{{ $dizimo->mes_referencia }}/{{ $dizimo->ano_referencia }}</td>
                            <td>{{ $dizimo->user->name ?? 'Sistema' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-view-{{ $dizimo->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-edit-{{ $dizimo->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('dizimos.destroy', $dizimo->id) }}" method="POST" style="display: inline;">
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

    <!-- Modal para Novo Dízimo -->
    <div class="modal fade" id="modal-novo-dizimo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Novo Dízimo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('dizimos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="membro_id">Membro</label>
                            <select class="form-control select2" id="membro_id" name="membro_id" required>
                                <option value="">Selecione um membro</option>
                                @foreach($membros ?? [] as $membro)
                                    <option value="{{ $membro->id }}">{{ $membro->nome }}</option>
                                @endforeach
                            </select>
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
                            <label for="data">Data do Pagamento</label>
                            <input type="date" class="form-control" id="data" name="data" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mes_referencia">Mês de Referência</label>
                                    <select class="form-control" id="mes_referencia" name="mes_referencia" required>
                                        <option value="1" {{ date('n') == 1 ? 'selected' : '' }}>Janeiro</option>
                                        <option value="2" {{ date('n') == 2 ? 'selected' : '' }}>Fevereiro</option>
                                        <option value="3" {{ date('n') == 3 ? 'selected' : '' }}>Março</option>
                                        <option value="4" {{ date('n') == 4 ? 'selected' : '' }}>Abril</option>
                                        <option value="5" {{ date('n') == 5 ? 'selected' : '' }}>Maio</option>
                                        <option value="6" {{ date('n') == 6 ? 'selected' : '' }}>Junho</option>
                                        <option value="7" {{ date('n') == 7 ? 'selected' : '' }}>Julho</option>
                                        <option value="8" {{ date('n') == 8 ? 'selected' : '' }}>Agosto</option>
                                        <option value="9" {{ date('n') == 9 ? 'selected' : '' }}>Setembro</option>
                                        <option value="10" {{ date('n') == 10 ? 'selected' : '' }}>Outubro</option>
                                        <option value="11" {{ date('n') == 11 ? 'selected' : '' }}>Novembro</option>
                                        <option value="12" {{ date('n') == 12 ? 'selected' : '' }}>Dezembro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ano_referencia">Ano de Referência</label>
                                    <input type="number" class="form-control" id="ano_referencia" name="ano_referencia" value="{{ date('Y') }}" min="2000" max="2100" required>
                                </div>
                            </div>
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
    @foreach($dizimos ?? [] as $dizimo)
        <!-- Modal de Visualização -->
        <div class="modal fade" id="modal-view-{{ $dizimo->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Detalhes do Dízimo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <dl class="row">
                            <dt class="col-sm-4">ID:</dt>
                            <dd class="col-sm-8">{{ $dizimo->id }}</dd>
                            
                            <dt class="col-sm-4">Membro:</dt>
                            <dd class="col-sm-8">{{ $dizimo->membro->nome ?? 'Não informado' }}</dd>
                            
                            <dt class="col-sm-4">Valor:</dt>
                            <dd class="col-sm-8">R$ {{ number_format($dizimo->valor, 2, ',', '.') }}</dd>
                            
                            <dt class="col-sm-4">Data:</dt>
                            <dd class="col-sm-8">{{ $dizimo->data->format('d/m/Y') }}</dd>
                            
                            <dt class="col-sm-4">Referência:</dt>
                            <dd class="col-sm-8">
                                @php
                                    $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                                    $mes = isset($meses[$dizimo->mes_referencia - 1]) ? $meses[$dizimo->mes_referencia - 1] : '';
                                @endphp
                                {{ $mes }}/{{ $dizimo->ano_referencia }}
                            </dd>
                            
                            <dt class="col-sm-4">Observação:</dt>
                            <dd class="col-sm-8">{{ $dizimo->observacao ?? 'Nenhuma observação' }}</dd>
                            
                            <dt class="col-sm-4">Registrado por:</dt>
                            <dd class="col-sm-8">{{ $dizimo->user->name ?? 'Sistema' }}</dd>
                            
                            <dt class="col-sm-4">Data de Registro:</dt>
                            <dd class="col-sm-8">{{ $dizimo->created_at->format('d/m/Y H:i:s') }}</dd>
                        </dl>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal-edit-{{ $dizimo->id }}">Editar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="modal-edit-{{ $dizimo->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title">Editar Dízimo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('dizimos.update', $dizimo->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="membro_id">Membro</label>
                                <select class="form-control select2" id="membro_id" name="membro_id" required>
                                    <option value="">Selecione um membro</option>
                                    @foreach($membros ?? [] as $membro)
                                        <option value="{{ $membro->id }}" {{ $dizimo->membro_id == $membro->id ? 'selected' : '' }}>{{ $membro->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="valor">Valor</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="number" step="0.01" min="0.01" class="form-control" id="valor" name="valor" value="{{ $dizimo->valor }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="data">Data do Pagamento</label>
                                <input type="date" class="form-control" id="data" name="data" value="{{ $dizimo->data->format('Y-m-d') }}" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mes_referencia">Mês de Referência</label>
                                        <select class="form-control" id="mes_referencia" name="mes_referencia" required>
                                            <option value="1" {{ $dizimo->mes_referencia == 1 ? 'selected' : '' }}>Janeiro</option>
                                            <option value="2" {{ $dizimo->mes_referencia == 2 ? 'selected' : '' }}>Fevereiro</option>
                                            <option value="3" {{ $dizimo->mes_referencia == 3 ? 'selected' : '' }}>Março</option>
                                            <option value="4" {{ $dizimo->mes_referencia == 4 ? 'selected' : '' }}>Abril</option>
                                            <option value="5" {{ $dizimo->mes_referencia == 5 ? 'selected' : '' }}>Maio</option>
                                            <option value="6" {{ $dizimo->mes_referencia == 6 ? 'selected' : '' }}>Junho</option>
                                            <option value="7" {{ $dizimo->mes_referencia == 7 ? 'selected' : '' }}>Julho</option>
                                            <option value="8" {{ $dizimo->mes_referencia == 8 ? 'selected' : '' }}>Agosto</option>
                                            <option value="9" {{ $dizimo->mes_referencia == 9 ? 'selected' : '' }}>Setembro</option>
                                            <option value="10" {{ $dizimo->mes_referencia == 10 ? 'selected' : '' }}>Outubro</option>
                                            <option value="11" {{ $dizimo->mes_referencia == 11 ? 'selected' : '' }}>Novembro</option>
                                            <option value="12" {{ $dizimo->mes_referencia == 12 ? 'selected' : '' }}>Dezembro</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ano_referencia">Ano de Referência</label>
                                        <input type="number" class="form-control" id="ano_referencia" name="ano_referencia" value="{{ $dizimo->ano_referencia }}" min="2000" max="2100" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label>
                                <textarea class="form-control" id="observacao" name="observacao" rows="3">{{ $dizimo->observacao }}</textarea>
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
        // Inicialização do Select2 para melhorar a usabilidade dos selects
        if($.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        }
        
        // Inicialização específica para a tabela de dízimos
        $('.datatable').DataTable({
            "order": [[3, 'desc']], // Ordenar por data (coluna 3) decrescente
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
