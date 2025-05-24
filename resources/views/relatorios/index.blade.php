@extends('adminlte::page')

@section('title', 'Relatórios')

@section('content_header')
    <h1><i class="fas fa-chart-line text-info"></i> Relatórios</h1>
@stop

@section('content')
<div class="card shadow">
    <div class="card-header bg-gradient-info text-white">
        <strong>Filtrar Relatórios</strong>
    </div>
    <div class="card-body">
        <form action="{{ route('relatorios.filtrar') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="data_inicio" class="form-label">Data Início</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    <input type="date" name="data_inicio" id="data_inicio" class="form-control" required>
                </div>
            </div>

            <div class="col-md-4">
                <label for="data_fim" class="form-label">Data Fim</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    <input type="date" name="data_fim" id="data_fim" class="form-control" required>
                </div>
            </div>

            <div class="col-md-4">
                <label for="tipo" class="form-label">Tipo de Relatório</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                    <select name="tipo" id="tipo" class="form-select" required>
                        <option value="">Selecione...</option>
                        <option value="entradas">Entradas</option>
                        <option value="saidas">Saídas</option>
                        <option value="resumo">Resumo</option>
                        <option value="membros">Membros</option>
                        <option value="despesas">Despesas</option>
                    </select>
                </div>
            </div>

            <div class="col-12 text-end mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Área para resultados / cards de dados --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body text-center text-muted">
                <i class="fas fa-info-circle fa-2x mb-2"></i>
                <p>Selecione o período e o tipo de relatório para visualizar os dados.</p>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .card {
            border-radius: 0.75rem;
        }
        .form-label {
            font-weight: bold;
        }
    </style>
@stop

@section('js')
    <script>
        console.log("Relatórios prontos para consulta.");
    </script>
@stop
