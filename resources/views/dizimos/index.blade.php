@extends('adminlte::page')

@section('title', 'Gerenciamento de Dízimos')

@section('content_header')
    <h1 class="text-dark">Gerenciamento de Dízimos</h1>
    <hr>
@stop

@section('content')
    <!-- Cards Resumo -->
    {{-- <div class="row mb-4">
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
                    <span class="info-box-number">R$ 
                        {{ ($totalDizimistasMesAtual ?? 0) > 0 
                            ? number_format(($totalDizimosMesAtual ?? 0) / $totalDizimistasMesAtual, 2, ',', '.') 
                            : '0,00' }}
                    </span>
                </div>
            </div>
        </div>
    </div> --}}

   

    <!-- Tabela de Presença Anual -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>Dizimista</th>
                    @for ($m = 1; $m <= 12; $m++)
                        <th>{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}/{{ now()->year }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach($dizimistas as $membro)
                    <tr>
                        <td class="text-start">{{ $membro->nome }}</td>
                        @for ($m = 1; $m <= 12; $m++)
                            @php
                                $contribuiu = $membro->dizimos
                                    ->where('ano_referencia', now()->year)
                                    ->where('mes_referencia', $m)
                                    ->isNotEmpty();
                            @endphp
                            <td>
                                @if ($contribuiu)
                                    <i class="fas fa-check text-success"></i>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Criar -->
    @include('dizimos.modals.create')
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        .bluebtn {
            background-color: rgb(3, 34, 75);
            color: white;
        }

        .bluebtn:hover {
            background-color: rgb(1, 35, 78);
            color: white;
        }

        .btn-warning {
            background-color: rgb(238, 255, 0);
            border-color: rgb(5, 16, 116);
            color: black;
        }

        .btn-danger {
            background-color: rgb(204, 14, 0);
            border-color: #F44336;
        }

        .dataTable thead th {
            background-color: #1E3A5F;
            color: #fff;
        }

        .modal-header {
            background-color: #1E3A5F;
            color: white;
        }

        .modal-footer {
            background-color: #f1f1f1;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(function () {
            $('.datatable').DataTable({
                responsive: true,
                order: [[3, 'desc']],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                }
            });
        });
    </script>
@stop
