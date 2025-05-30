@extends('adminlte::page')

@section('title', 'Gerenciamento de Caixa')

@section('content_header')
    <h1 class="text-dark">Gerenciamento de Caixa</h1>
    <hr>
@stop

@section('content')
    <div class="row mb-4">
        <div class="col d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modal-entrada">
                <i class="fas fa-plus-circle"></i> Nova Entrada
            </button>
            <button type="button" class="btn btn-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modal-saida">
                <i class="fas fa-minus-circle"></i> Nova Saída
            </button>
        </div>
    </div>

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

    <!-- Tabela de Movimentações -->
    @component('components.data-table', [
        'responsive' => [
            ['responsivePriority' => 1, 'targets' => 0],
            ['responsivePriority' => 2, 'targets' => 1],
            ['responsivePriority' => 3, 'targets' => -1],
        ],
        'itemsPerPage' => 10,
        'showTotal' => false,
        'valueColumnIndex' => 5,
    ])
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
                        <button type="button" class="btn btn-info btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modal-view-{{ $movimentacao->id }}">
                            👁️ Visualizar
                        </button>
                        <button type="button" class="btn btn-warning btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $movimentacao->id }}">
                            ✏️ Editar
                        </button>
                        <button type="button" class="btn btn-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modal-delete-{{ $movimentacao->id }}">
                            🗑️ Excluir
                        </button>
                    </td>
                </tr>

                {{-- Modais --}}
                @include('caixa.modals.view', ['movimentacao' => $movimentacao])
                @include('caixa.modals.edit', ['movimentacao' => $movimentacao])
                @include('caixa.modals.delete', ['movimentacao' => $movimentacao])
            @endforeach
        </tbody>
    @endcomponent

    {{-- Modais de Entrada e Saída --}}
    @include('caixa.modals.novaEntrada')
    @include('caixa.modals.novaSaida')
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
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
            color: white;
        }

        .modal-header {
            background-color: #1E3A5F;
            color: #fff;
        }

        .modal-footer {
            background-color: #f1f1f1;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
@stop
