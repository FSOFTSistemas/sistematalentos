@extends('adminlte::page')

@section('title', 'Balanço Financeiro da Igreja')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Balanço Financeiro</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Balanço Financeiro da Igreja</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('relatorios.balanco') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="periodo">Período</label>
                        <select name="periodo" id="periodo" class="form-control">
                            <option value="mensal" {{ request('periodo') == 'mensal' ? 'selected' : '' }}>Mensal</option>
                            <option value="trimestral" {{ request('periodo') == 'trimestral' ? 'selected' : '' }}>Trimestral</option>
                            <option value="semestral" {{ request('periodo') == 'semestral' ? 'selected' : '' }}>Semestral</option>
                            <option value="anual" {{ request('periodo') == 'anual' ? 'selected' : '' }}>Anual</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="data_inicio">Data Início</label>
                        <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{ request('data_inicio', date('Y-m-01')) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="data_fim">Data Fim</label>
                        <input type="date" class="form-control" id="data_fim" name="data_fim" value="{{ request('data_fim', date('Y-m-t')) }}">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filtrar
            </button>
            <a href="{{ route('relatorios.balanco.pdf', ['periodo' => request('periodo', 'mensal'), 'data_inicio' => request('data_inicio', date('Y-m-01')), 'data_fim' => request('data_fim', date('Y-m-t'))]) }}" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
            <a href="{{ route('relatorios.balanco.excel', ['periodo' => request('periodo', 'mensal'), 'data_inicio' => request('data_inicio', date('Y-m-01')), 'data_fim' => request('data_fim', date('Y-m-t'))]) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Exportar Excel
            </a>
        </form>

        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total de Entradas</span>
                        <span class="info-box-number">R$ {{ number_format($total_entradas ?? 0, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-danger">
                    <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total de Saídas</span>
                        <span class="info-box-number">R$ {{ number_format($total_saidas ?? 0, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box {{ ($saldo ?? 0) >= 0 ? 'bg-info' : 'bg-warning' }}">
                    <span class="info-box-icon"><i class="fas fa-balance-scale"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Saldo do Período</span>
                        <span class="info-box-number">R$ {{ number_format($saldo ?? 0, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Evolução Financeira</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoEvolucaoFinanceira" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Entradas por Categoria</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoEntradasCategoria" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Saídas por Categoria</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoSaidasCategoria" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-resumo-tab" data-toggle="pill" href="#tab-resumo" role="tab" aria-controls="tab-resumo" aria-selected="true">Resumo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-entradas-tab" data-toggle="pill" href="#tab-entradas" role="tab" aria-controls="tab-entradas" aria-selected="false">Entradas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-saidas-tab" data-toggle="pill" href="#tab-saidas" role="tab" aria-controls="tab-saidas" aria-selected="false">Saídas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-dizimos-tab" data-toggle="pill" href="#tab-dizimos" role="tab" aria-controls="tab-dizimos" aria-selected="false">Dízimos</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="tab-resumo" role="tabpanel" aria-labelledby="tab-resumo-tab">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Categoria</th>
                                                <th>Entradas</th>
                                                <th>Saídas</th>
                                                <th>Saldo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($resumo_categorias ?? [] as $categoria => $valores)
                                            <tr>
                                                <td>{{ ucfirst($categoria) }}</td>
                                                <td class="text-success">R$ {{ number_format($valores['entradas'] ?? 0, 2, ',', '.') }}</td>
                                                <td class="text-danger">R$ {{ number_format($valores['saidas'] ?? 0, 2, ',', '.') }}</td>
                                                <td class="{{ ($valores['entradas'] - $valores['saidas']) >= 0 ? 'text-info' : 'text-warning' }}">
                                                    R$ {{ number_format(($valores['entradas'] - $valores['saidas']), 2, ',', '.') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                            <tr class="font-weight-bold">
                                                <td>Total</td>
                                                <td class="text-success">R$ {{ number_format($total_entradas ?? 0, 2, ',', '.') }}</td>
                                                <td class="text-danger">R$ {{ number_format($total_saidas ?? 0, 2, ',', '.') }}</td>
                                                <td class="{{ ($saldo ?? 0) >= 0 ? 'text-info' : 'text-warning' }}">
                                                    R$ {{ number_format($saldo ?? 0, 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-entradas" role="tabpanel" aria-labelledby="tab-entradas-tab">
                                <table class="table table-hover text-nowrap datatable">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Descrição</th>
                                            <th>Categoria</th>
                                            <th>Valor</th>
                                            <th>Registrado por</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($entradas ?? [] as $entrada)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($entrada->data)->format('d/m/Y') }}</td>
                                            <td>{{ $entrada->descricao }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($entrada->categoria == 'dizimo') badge-primary 
                                                    @elseif($entrada->categoria == 'oferta') badge-success 
                                                    @elseif($entrada->categoria == 'doacao') badge-info 
                                                    @else badge-secondary 
                                                    @endif">
                                                    {{ ucfirst($entrada->categoria) }}
                                                </span>
                                            </td>
                                            <td>R$ {{ number_format($entrada->valor, 2, ',', '.') }}</td>
                                            <td>{{ $entrada->user->name ?? 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Nenhuma entrada encontrada</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="tab-saidas" role="tabpanel" aria-labelledby="tab-saidas-tab">
                                <table class="table table-hover text-nowrap datatable">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Descrição</th>
                                            <th>Categoria</th>
                                            <th>Valor</th>
                                            <th>Registrado por</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($saidas ?? [] as $saida)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($saida->data)->format('d/m/Y') }}</td>
                                            <td>{{ $saida->descricao }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($saida->categoria == 'administrativa') badge-warning 
                                                    @elseif($saida->categoria == 'manutencao') badge-info 
                                                    @elseif($saida->categoria == 'eventos') badge-primary 
                                                    @else badge-secondary 
                                                    @endif">
                                                    {{ ucfirst($saida->categoria) }}
                                                </span>
                                            </td>
                                            <td>R$ {{ number_format($saida->valor, 2, ',', '.') }}</td>
                                            <td>{{ $saida->user->name ?? 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Nenhuma saída encontrada</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="tab-dizimos" role="tabpanel" aria-labelledby="tab-dizimos-tab">
                                <table class="table table-hover text-nowrap datatable">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Membro</th>
                                            <th>Referência</th>
                                            <th>Valor</th>
                                            <th>Registrado por</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dizimos ?? [] as $dizimo)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($dizimo->data)->format('d/m/Y') }}</td>
                                            <td>{{ $dizimo->membro->nome ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                                                @endphp
                                                {{ $meses[$dizimo->mes_referencia-1] }}/{{ $dizimo->ano_referencia }}
                                            </td>
                                            <td>R$ {{ number_format($dizimo->valor, 2, ',', '.') }}</td>
                                            <td>{{ $dizimo->user->name ?? 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Nenhum dízimo encontrado</td>
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
    </div>
</div>
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

        // Atualizar data fim automaticamente ao mudar o período
        $('#periodo').change(function() {
            var dataInicio = new Date($('#data_inicio').val());
            var dataFim = new Date(dataInicio);
            
            switch($(this).val()) {
                case 'mensal':
                    dataFim = new Date(dataInicio.getFullYear(), dataInicio.getMonth() + 1, 0);
                    break;
                case 'trimestral':
                    dataFim = new Date(dataInicio.getFullYear(), dataInicio.getMonth() + 3, 0);
                    break;
                case 'semestral':
                    dataFim = new Date(dataInicio.getFullYear(), dataInicio.getMonth() + 6, 0);
                    break;
                case 'anual':
                    dataFim = new Date(dataInicio.getFullYear() + 1, dataInicio.getMonth(), 0);
                    break;
            }
            
            $('#data_fim').val(dataFim.toISOString().split('T')[0]);
        });

        // Gráfico de Evolução Financeira
        var ctxEvolucao = document.getElementById('graficoEvolucaoFinanceira').getContext('2d');
        var chartEvolucao = new Chart(ctxEvolucao, {
            type: 'line',
            data: {
                labels: {!! json_encode($periodos ?? []) !!},
                datasets: [
                    {
                        label: 'Entradas',
                        data: {!! json_encode($valores_entradas_periodo ?? []) !!},
                        backgroundColor: 'rgba(40, 167, 69, 0.2)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 2,
                        tension: 0.1
                    },
                    {
                        label: 'Saídas',
                        data: {!! json_encode($valores_saidas_periodo ?? []) !!},
                        backgroundColor: 'rgba(220, 53, 69, 0.2)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 2,
                        tension: 0.1
                    },
                    {
                        label: 'Saldo',
                        data: {!! json_encode($valores_saldo_periodo ?? []) !!},
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 2,
                        tension: 0.1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toFixed(2).replace('.', ',');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': R$ ' + context.raw.toFixed(2).replace('.', ',');
                            }
                        }
                    }
                }
            }
        });

        // Gráfico de Entradas por Categoria
        var ctxEntradasCategoria = document.getElementById('graficoEntradasCategoria').getContext('2d');
        var chartEntradasCategoria = new Chart(ctxEntradasCategoria, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categorias_entradas ?? []) !!},
                datasets: [{
                    data: {!! json_encode($valores_categorias_entradas ?? []) !!},
                    backgroundColor: [
                        'rgba(0, 123, 255, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(108, 117, 125, 0.8)'
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
                                return label + ': R$ ' + value.toFixed(2).replace('.', ',') + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Gráfico de Saídas por Categoria
        var ctxSaidasCategoria = document.getElementById('graficoSaidasCategoria').getContext('2d');
        var chartSaidasCategoria = new Chart(ctxSaidasCategoria, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categorias_saidas ?? []) !!},
                datasets: [{
                    data: {!! json_encode($valores_categorias_saidas ?? []) !!},
                    backgroundColor: [
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(0, 123, 255, 0.8)',
                        'rgba(108, 117, 125, 0.8)'
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
                                return label + ': R$ ' + value.toFixed(2).replace('.', ',') + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
