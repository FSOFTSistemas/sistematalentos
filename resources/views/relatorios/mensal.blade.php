@extends('adminlte::page')

@section('title', 'Relatório Mensal de Entradas e Saídas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Relatório Mensal</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Relatório Mensal de Entradas e Saídas</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('relatorios.mensal') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mes">Mês</label>
                            <select name="mes" id="mes" class="form-control">
                                <option value="1" {{ request('mes', date('m')) == 1 ? 'selected' : '' }}>Janeiro
                                </option>
                                <option value="2" {{ request('mes', date('m')) == 2 ? 'selected' : '' }}>Fevereiro
                                </option>
                                <option value="3" {{ request('mes', date('m')) == 3 ? 'selected' : '' }}>Março</option>
                                <option value="4" {{ request('mes', date('m')) == 4 ? 'selected' : '' }}>Abril</option>
                                <option value="5" {{ request('mes', date('m')) == 5 ? 'selected' : '' }}>Maio</option>
                                <option value="6" {{ request('mes', date('m')) == 6 ? 'selected' : '' }}>Junho</option>
                                <option value="7" {{ request('mes', date('m')) == 7 ? 'selected' : '' }}>Julho</option>
                                <option value="8" {{ request('mes', date('m')) == 8 ? 'selected' : '' }}>Agosto
                                </option>
                                <option value="9" {{ request('mes', date('m')) == 9 ? 'selected' : '' }}>Setembro
                                </option>
                                <option value="10" {{ request('mes', date('m')) == 10 ? 'selected' : '' }}>Outubro
                                </option>
                                <option value="11" {{ request('mes', date('m')) == 11 ? 'selected' : '' }}>Novembro
                                </option>
                                <option value="12" {{ request('mes', date('m')) == 12 ? 'selected' : '' }}>Dezembro
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ano">Ano</label>
                            <select name="ano" id="ano" class="form-control">
                                @for ($i = date('Y') - 5; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}"
                                        {{ request('ano', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="categoria">Categoria</label>
                            <select name="categoria" id="categoria" class="form-control">
                                <option value="">Todas</option>
                                <option value="dizimo" {{ request('categoria') == 'dizimo' ? 'selected' : '' }}>Dízimos
                                </option>
                                <option value="oferta" {{ request('categoria') == 'oferta' ? 'selected' : '' }}>Ofertas
                                </option>
                                <option value="doacao" {{ request('categoria') == 'doacao' ? 'selected' : '' }}>Doações
                                </option>
                                <option value="administrativa"
                                    {{ request('categoria') == 'administrativa' ? 'selected' : '' }}>Administrativas
                                </option>
                                <option value="manutencao" {{ request('categoria') == 'manutencao' ? 'selected' : '' }}>
                                    Manutenção</option>
                                <option value="eventos" {{ request('categoria') == 'eventos' ? 'selected' : '' }}>Eventos
                                </option>
                                <option value="outras" {{ request('categoria') == 'outras' ? 'selected' : '' }}>Outras
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Filtrar
                </button>
                <a href="{{ route('relatorios.mensal.pdf', ['mes' => request('mes', date('m')), 'ano' => request('ano', date('Y')), 'categoria' => request('categoria')]) }}"
                    class="btn btn-danger" target="_blank">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
                <a href="{{ route('relatorios.mensal.excel', ['mes' => request('mes', date('m')), 'ano' => request('ano', date('Y')), 'categoria' => request('categoria')]) }}"
                    class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
            </form>

            <div class="row">
                <div class="col-md-6">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total de Entradas</span>
                            <span class="info-box-number">R$ {{ number_format($total_entradas ?? 0, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total de Saídas</span>
                            <span class="info-box-number">R$ {{ number_format($total_saidas ?? 0, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

           

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Entradas</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
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
                                                <span
                                                    class="badge 
                                            @if ($entrada->categoria == 'dizimo') badge-primary 
                                            @elseif($entrada->categoria == 'oferta') badge-success 
                                            @elseif($entrada->categoria == 'doacao') badge-info 
                                            @else badge-secondary @endif">
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
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Saídas</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
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
                                                <span
                                                    class="badge 
                                            @if ($saida->categoria == 'administrativa') badge-warning 
                                            @elseif($saida->categoria == 'manutencao') badge-info 
                                            @elseif($saida->categoria == 'eventos') badge-primary 
                                            @else badge-secondary @endif">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        
        $(function() {

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

           
        });
    </script>

    

@endsection

