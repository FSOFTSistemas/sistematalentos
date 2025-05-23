@extends('adminlte::page')

@section('title', 'Gerenciamento de Planos')

@section('content_header')
    <h1 class="text-dark">Gerenciamento de Planos</h1>
    <hr>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col">
            <a class="btn float-end rounded-pill" data-bs-toggle="modal" data-bs-target="#modal-novo-plano">
                
                <i class="fa fa-plus"></i> Novo Plano
            </a>
        </div>
    </div>

    <!-- DataTable Customizado -->
    @component('components.data-table', [
        'responsive' => [
            ['responsivePriority' => 1, 'targets' => 0],
            ['responsivePriority' => 2, 'targets' => 1],
            ['responsivePriority' => 3, 'targets' => 2],
            ['responsivePriority' => 4, 'targets' => -1],
        ],
        'itemsPerPage' => 10,
        'showTotal' => false,
        'valueColumnIndex' => 1,
    ])
        <thead style="background-color: #1E3A5F;">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Valor</th>
                <th>Período</th>
                <th>Limite de Membros</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($planos ?? [] as $plano)
                <tr>
                    <td>{{ $plano->id }}</td>
                    <td>{{ $plano->nome }}</td>
                    <td>R$ {{ number_format($plano->valor, 2, ',', '.') }}</td>
                    <td>{{ $plano->periodoFormatado }}</td>
                    <td>{{ $plano->limite_membros }}</td>
                    <td>
                        @if($plano->ativo)
                            <span class="badge badge-success">Ativo</span>
                        @else
                            <span class="badge badge-danger">Inativo</span>
                        @endif
                    </td>
                    <td>
                        <!-- Botão Editar -->
                        <button type="button" class="btn btn-warning btn-sm rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#modal-edit-{{ $plano->id }}">
                            ✏️ Editar
                        </button>
                        <!-- Botão Excluir -->
                        <button type="button" class="btn btn-danger btn-sm rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#deletePlanoModal{{ $plano->id }}">
                            🗑️ Excluir
                        </button>
                    </td>
                </tr>

                <!-- Modal Editar -->
                @include('planos.modals.edit', ['plano' => $plano])

                <!-- Modal Excluir -->
                @include('planos.modals.delete', ['plano' => $plano])
            @endforeach
        </tbody>
    @endcomponent

    <!-- Modal Criar -->
    @include('planos.modals.create')
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* Custom Styles */
        .btn-primary {
            background-color: rgb(3, 34, 75);
            border-color: rgb(1, 35, 78);
        }

        .btn-primary:hover {
            background-color: rgb(3, 37, 68);
            border-color: rgb(3, 39, 73);
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

        .btn-sm {
            padding: 6px 12px;
        }

        .modal-header {
            background-color: #1E3A5F;
            color: #fff;
        }

        .modal-footer {
            background-color: #f1f1f1;
        }

        .dataTable thead th {
            background-color: #1E3A5F;
            color: #fff;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 4px;
            border: 1px solid #1E3A5F;
        }

        /* Optional: ajustar o botão novo plano azul como no seu exemplo */
        .bluebtn {
            background-color: rgb(3, 34, 75);
            color: white;
        }

        .bluebtn:hover {
            background-color: rgb(1, 35, 78);
            color: white;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Exibir notificação de sucesso
        @if (session('success'))
            Swal.fire({
                title: 'Sucesso!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        $(document).ready(function() {
            $('.select2').select2();

            // Inicialização do DataTable
            $('.data-table').DataTable({
                responsive: true,
                order: [[0, 'asc']], // Ordenar pelo ID
                pageLength: 10, // Itens por página
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json' // Tradução para o português
                }
            });
        });
    </script>
@stop
