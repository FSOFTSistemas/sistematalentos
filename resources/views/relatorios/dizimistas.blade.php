@extends('adminlte::page')

@section('title', 'Relatório de Dizimistas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Relatório de Dizimistas</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Relatório de Dizimistas com Status de Pagamento</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('relatorios.dizimistas') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="mes">Mês</label>
                        <select name="mes" id="mes" class="form-control">
                            <option value="1" {{ request('mes', date('m')) == 1 ? 'selected' : '' }}>Janeiro</option>
                            <option value="2" {{ request('mes', date('m')) == 2 ? 'selected' : '' }}>Fevereiro</option>
                            <option value="3" {{ request('mes', date('m')) == 3 ? 'selected' : '' }}>Março</option>
                            <option value="4" {{ request('mes', date('m')) == 4 ? 'selected' : '' }}>Abril</option>
                            <option value="5" {{ request('mes', date('m')) == 5 ? 'selected' : '' }}>Maio</option>
                            <option value="6" {{ request('mes', date('m')) == 6 ? 'selected' : '' }}>Junho</option>
                            <option value="7" {{ request('mes', date('m')) == 7 ? 'selected' : '' }}>Julho</option>
                            <option value="8" {{ request('mes', date('m')) == 8 ? 'selected' : '' }}>Agosto</option>
                            <option value="9" {{ request('mes', date('m')) == 9 ? 'selected' : '' }}>Setembro</option>
                            <option value="10" {{ request('mes', date('m')) == 10 ? 'selected' : '' }}>Outubro</option>
                            <option value="11" {{ request('mes', date('m')) == 11 ? 'selected' : '' }}>Novembro</option>
                            <option value="12" {{ request('mes', date('m')) == 12 ? 'selected' : '' }}>Dezembro</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ano">Ano</label>
                        <select name="ano" id="ano" class="form-control">
                            @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ request('ano', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">Todos</option>
                            <option value="pago" {{ request('status') == 'pago' ? 'selected' : '' }}>Pagos</option>
                            <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendentes</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filtrar
            </button>
            <a href="{{ route('relatorios.dizimistas.pdf', ['mes' => request('mes', date('m')), 'ano' => request('ano', date('Y')), 'status' => request('status')]) }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('relatorios.dizimistas.excel', ['mes' => request('mes', date('m')), 'ano' => request('ano', date('Y')), 'status' => request('status')]) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </form>

        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total de Membros</span>
                        <span class="info-box-number">{{ $total_membros ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dízimos Pagos</span>
                        <span class="info-box-number">{{ $total_pagos ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-times"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dízimos Pendentes</span>
                        <span class="info-box-number">{{ $total_pendentes ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Gráfico de Status de Pagamento</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoStatusPagamento" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Histórico de Pagamentos</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoHistoricoPagamentos" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lista de Dizimistas</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Contato</th>
                                    <th>Status</th>
                                    <th>Valor</th>
                                    <th>Data de Pagamento</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dizimistas ?? [] as $dizimista)
                                <tr>
                                    <td>{{ $dizimista->membro->nome ?? 'N/A' }}</td>
                                    <td>{{ $dizimista->membro->telefone ?? 'N/A' }}</td>
                                    <td>
                                        @if(isset($dizimista->status) && $dizimista->status == 'pago')
                                            <span class="badge badge-success">Pago</span>
                                        @else
                                            <span class="badge badge-danger">Pendente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($dizimista->valor))
                                            R$ {{ number_format($dizimista->valor, 2, ',', '.') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($dizimista->data_pagamento))
                                            {{ \Carbon\Carbon::parse($dizimista->data_pagamento)->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if(!isset($dizimista->status) || $dizimista->status != 'pago')
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-registrar-dizimo-{{ $dizimista->membro->id ?? 'N/A' }}">
                                                <i class="fas fa-money-bill"></i> Registrar
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-detalhes-dizimo-{{ $dizimista->id }}">
                                                <i class="fas fa-eye"></i> Detalhes
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Nenhum dizimista encontrado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modais para Registrar Dízimo -->
@foreach($membros_pendentes ?? [] as $membro)
<div class="modal fade" id="modal-registrar-dizimo-{{ $membro->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">Registrar Dízimo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('dizimos.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="membro_id" value="{{ $membro->id }}">
                    <input type="hidden" name="mes_referencia" value="{{ request('mes', date('m')) }}">
                    <input type="hidden" name="ano_referencia" value="{{ request('ano', date('Y')) }}">
                    
                    <div class="form-group">
                        <label>Membro</label>
                        <p class="form-control-static">{{ $membro->nome }}</p>
                    </div>
                    
                    <div class="form-group">
                        <label>Referência</label>
                        <p class="form-control-static">
                            @php
                                $mes = request('mes', date('m'));
                                $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                            @endphp
                            {{ $meses[$mes-1] }}/{{ request('ano', date('Y')) }}
                        </p>
                    </div>
                    
                    <div class="form-group">
                        <label for="valor">Valor</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">R$</span>
                            </div>
                            <input type="text" class="form-control" id="valor" name="valor" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="data">Data de Pagamento</label>
                        <input type="date" class="form-control" id="data" name="data" value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="observacao">Observação</label>
                        <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Modais para Detalhes do Dízimo -->
@foreach($dizimos_pagos ?? [] as $dizimo)
<div class="modal fade" id="modal-detalhes-dizimo-{{ $dizimo->id }}">
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
                    <dt class="col-sm-4">Membro:</dt>
                    <dd class="col-sm-8">{{ $dizimo->membro->nome ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-4">Valor:</dt>
                    <dd class="col-sm-8">R$ {{ number_format($dizimo->valor, 2, ',', '.') }}</dd>
                    
                    <dt class="col-sm-4">Data de Pagamento:</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($dizimo->data)->format('d/m/Y') }}</dd>
                    
                    <dt class="col-sm-4">Referência:</dt>
                    <dd class="col-sm-8">
                        @php
                            $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                        @endphp
                        {{ $meses[$dizimo->mes_referencia-1] }}/{{ $dizimo->ano_referencia }}
                    </dd>
                    
                    <dt class="col-sm-4">Registrado por:</dt>
                    <dd class="col-sm-8">{{ $dizimo->user->name ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-4">Data de Registro:</dt>
                    <dd class="col-sm-8">{{ $dizimo->created_at->format('d/m/Y H:i:s') }}</dd>
                    
                    @if($dizimo->observacao)
                        <dt class="col-sm-4">Observação:</dt>
                        <dd class="col-sm-8">{{ $dizimo->observacao }}</dd>
                    @endif
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <a href="{{ route('dizimos.recibo', $dizimo->id) }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-print"></i> Imprimir Recibo
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function () {
        // Inicialização das DataTables
        $('.datatable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
            }
        });

        // Máscara para campo de valor
        $('#valor').mask('#.##0,00', {reverse: true});

        // Gráfico de Status de Pagamento
        var ctxStatus = document.getElementById('graficoStatusPagamento').getContext('2d');
        var chartStatus = new Chart(ctxStatus, {
            type: 'pie',
            data: {
                labels: ['Pagos', 'Pendentes'],
                datasets: [{
                    data: [{{ $total_pagos ?? 0 }}, {{ $total_pendentes ?? 0 }}],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                var percentage = Math.round((value / total) * 100);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Gráfico de Histórico de Pagamentos
        var ctxHistorico = document.getElementById('graficoHistoricoPagamentos').getContext('2d');
        var chartHistorico = new Chart(ctxHistorico, {
            type: 'line',
            data: {
                labels: {!! json_encode($meses_historico ?? []) !!},
                datasets: [{
                    label: 'Dízimos Pagos',
                    data: {!! json_encode($valores_historico ?? []) !!},
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' membros';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw + ' membros';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
