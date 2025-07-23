@extends('adminlte::page')

@section('title', 'Gerenciamento de Membros')

@section('content_header')
    <h1 class="text-dark">Gerenciamento de Membros</h1>
    <hr>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col d-flex justify-content-end gap-2">
            <a class="btn rounded-pill bluebtn" href="{{ route('membros.create') }}">
                <i class="fa fa-plus"></i> Novo Membro
            </a>
            <a class="btn rounded-pill bluebtn" href="{{ route('membros.imprimir') }}" title="Imprimir" target="_blank">
                <i class="fa fa-print"></i>
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
        'itemsPerPage' => 25,
        'showTotal' => false,
        'valueColumnIndex' => 1,
    ])
        <thead style="background-color: #1E3A5F;">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($membros ?? [] as $membro)
                <tr>
                    <td>{{ $membro->id }}</td>
                    <td>{{ $membro->nome }}</td>
                    <td>{{ $membro->telefone ?? 'Não informado' }}</td>
                    <td>{{ $membro->email ?? 'Não informado' }}</td>
                    <td>
                        @if ($membro->status == 'ativo')
                            <span class="badge badge-success">Ativo</span>
                        @else
                            <span class="badge badge-secondary">Inativo</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#modal-view-{{ $membro->id }}">
                            👁️ Ver
                        </button>
                        <a href="{{ route('membros.edit', $membro->id) }}" class="btn btn-warning btn-sm rounded-pill">
                            ✏️ Editar
                        </a>

                        <button type="button" class="btn btn-danger btn-sm rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#modal-delete-{{ $membro->id }}">
                            🗑️ Excluir
                        </button>

                    </td>
                </tr>

                {{-- Modais --}}
                @include('membros.modals.delete', ['membro' => $membro])
                @include('membros.modals.view', ['membro' => $membro])
            @endforeach
        </tbody>
    @endcomponent

@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        .btn-primary {
            background-color: rgb(3, 34, 75);
            border-color: rgb(1, 35, 78);
        }

        .btn-primary:hover {
            background-color: rgb(3, 37, 68);
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

        .btn-info {
            background-color: #17a2b8;
            border-color: #138496;
        }

        .btn-sm {
            padding: 6px 12px;
        }

        .modal-header {
            background-color: #1E3A5F;
            color: #fff;
        }

        .dataTable thead th {
            background-color: #1E3A5F;
            color: #fff;
        }

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
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            if ($.fn.inputmask) {
                $('#cpf').inputmask('999.999.999-99');
                $('#telefone').inputmask('(99) 99999-9999');
                $('#cep').inputmask('99999-999');
            }

        });
    </script>

@stop
