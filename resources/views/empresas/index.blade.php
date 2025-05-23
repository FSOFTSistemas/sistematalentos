<?php
use Carbon\Carbon;
?>

@extends('adminlte::page')

@section('title', 'Gerenciamento de Empresas')

@section('content_header')
    <h1>Gerenciamento de Empresas/Igrejas</h1>
    <hr>
@stop

@section('content')
    <div class="row mb-3">
        <div class="col text-end">
            <a href="{{ route('empresas.create') }}" class="btn btn-primary rounded-pill shadow-sm">
                <i class="fas fa-plus me-1"></i> Nova Empresa
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
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Plano</th>
                <th>Membros</th>
                <th>Validade</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($empresas ?? [] as $empresa)
                <tr>
                    <td>{{ $empresa->id }}</td>
                    <td>{{ $empresa->nome }}</td>
                    <td>{{ $empresa->plano->nome ?? 'Sem plano' }}</td>
                    <td>
                        @if ($empresa->plano)
                            {{ $empresa->membros()->count() }} / {{ $empresa->plano->limite_membros }}
                            @php
                                $percentual =
                                    $empresa->plano->limite_membros > 0
                                        ? min(
                                            100,
                                            ($empresa->membros()->count() / $empresa->plano->limite_membros) * 100,
                                        )
                                        : 0;
                                $colorClass =
                                    $percentual < 70 ? 'bg-success' : ($percentual < 90 ? 'bg-warning' : 'bg-danger');
                            @endphp
                            <div class="progress progress-xs mt-1">
                                <div class="progress-bar {{ $colorClass }}" style="width: {{ $percentual }}%"></div>
                            </div>
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if ($empresa->data_fim_plano)
                            {{ Carbon::parse($empresa->data_fim_plano)->format('d/m/Y') }}
                            @if (Carbon::parse($empresa->data_fim_plano)->isPast())
                                <span class="badge bg-danger">Vencido</span>
                            @elseif(Carbon::parse($empresa->data_fim_plano)->diffInDays(now()) < 15)
                                <span class="badge bg-warning">Próximo ao vencimento</span>
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                    <td>
                        @if ($empresa->ativo && $empresa->planoAtivo())
                            <span class="badge bg-success">Ativo</span>
                        @else
                            <span class="badge bg-danger">Inativo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('empresas.show', $empresa->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>

                        {{-- <a href="{{ route('empresas.edit', $empresa->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a> --}}
                        <form action="{{ route('empresas.destroy', $empresa->id) }}" method="POST"
                            class="d-inline-block btn-delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm btn-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                @include('empresas.modals.newUser', ['empresa' => $empresa])
            @endforeach
        </tbody>
    @endcomponent


@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $('.btn-delete').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                Swal.fire({
                    title: 'Tem certeza?',
                    text: 'Esta ação não poderá ser desfeita!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if (session('success'))
            Swal.fire({
                title: 'Sucesso!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif
    </script>
@stop
