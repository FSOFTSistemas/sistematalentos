

@extends('adminlte::page')

@section('title', 'Patrimônios da Igreja')

@section('content_header')
    <h1 class="text-dark">Gerenciamento de Patrimônios</h1>
    <hr>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col">
            <a class="btn float-end rounded-pill bluebtn" href="{{ route('patrimonios.create') }}">
                <i class="fa fa-plus"></i> Novo Patrimônio
            </a>
        </div>
    </div>

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
        <thead>
            <tr>
                <th>ID</th>
                <th>Descrição</th>
                <th>Nº Patrimônio</th>
                <th>Categoria</th>
                <th>Localização</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($patrimonios ?? [] as $patrimonio)
                <tr>
                    <td>{{ $patrimonio->id }}</td>
                    <td>{{ $patrimonio->descricao }}</td>
                    <td>{{ $patrimonio->numero_patrimonio }}</td>
                    <td>{{ $patrimonio->categoria->nome ?? '—' }}</td>
                    <td>{{ $patrimonio->localizacao ?? '—' }}</td>
                    <td>
                        @if ($patrimonio->ativo)
                            <span class="badge badge-success">Ativo</span>
                        @else
                            <span class="badge badge-secondary">Inativo</span>
                        @endif
                    </td>
                    <td>
                        {{-- <a href="{{ route('patrimonios.show', $patrimonio->id) }}" class="btn btn-info btn-sm rounded-pill">👁️ Ver</a> --}}
                        <a href="{{ route('patrimonios.edit', $patrimonio->id) }}" class="btn btn-warning btn-sm rounded-pill">✏️ Editar</a>
                        <form action="{{ route('patrimonios.destroy', $patrimonio->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir este patrimônio?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm rounded-pill">🗑️ Excluir</button>
                        </form>
                    </td>
                </tr>
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
@stop