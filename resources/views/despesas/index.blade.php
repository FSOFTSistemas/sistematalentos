@extends('adminlte::page')

@section('title', 'Gerenciamento de Despesas')

@section('content_header')
    <h1 class="text-dark">Gerenciamento de Despesas</h1>
    <hr>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col">
            <button class="btn btn-primary float-end rounded-pill" data-bs-toggle="modal" data-bs-target="#modal-nova-despesa">
                <i class="fa fa-plus"></i> Nova Despesa
            </button>
        </div>
    </div>

    <!-- Cards Informativos -->
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

    <!-- DataTable -->
    @component('components.data-table', [
        'responsive' => [
            ['responsivePriority' => 1, 'targets' => 0],
            ['responsivePriority' => 2, 'targets' => 1],
            ['responsivePriority' => 3, 'targets' => 3],
            ['responsivePriority' => 4, 'targets' => -1],
        ],
        'itemsPerPage' => 10,
        'showTotal' => false,
        'valueColumnIndex' => 1
    ])
        <thead style="background-color: #1E3A5F;">
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
                    <td>{{ optional($despesa->data)->format('d/m/Y') ?? 'Não informado' }}</td>
                    <td>{{ optional($despesa->data_vencimento)->format('d/m/Y') ?? 'Não informado' }}</td>
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
                        <!-- Botões Ações -->
                        <button class="btn btn-info btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modal-view-{{ $despesa->id }}">
                            👁️
                        </button>
                        <button class="btn btn-warning btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $despesa->id }}">
                            ✏️
                        </button>
                        <button class="btn btn-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modal-delete-{{ $despesa->id }}">
                            🗑️
                        </button>
                    </td>
                </tr>

                @include('despesas.modals.view', ['despesa' => $despesa])
                @include('despesas.modals.edit', ['despesa' => $despesa])
                @include('despesas.modals.delete', ['despesa' => $despesa])
            @endforeach
        </tbody>
    @endcomponent

    <!-- Modal Nova Despesa -->
    @include('despesas.modals.create')
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        .btn-primary {
            background-color: rgb(3, 34, 75);
            border-color: rgb(1, 35, 78);
        }
        .btn-warning {
            background-color: rgb(238, 255, 0);
            color: black;
        }
        .btn-danger {
            background-color: rgb(204, 14, 0);
        }
        .btn-sm {
            padding: 6px 12px;
        }
        .modal-header {
            background-color: #1E3A5F;
            color: #fff;
        }
    </style>
@stop

@section('js')
    <script>
        // Adicione aqui qualquer JS específico, se necessário.
    </script>
@stop
