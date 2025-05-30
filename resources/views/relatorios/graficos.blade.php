@extends('adminlte::page')

@section('title', 'Gráficos')

@section('content_header')
    <h1 class="text-dark">Gráficos</h1>
    <hr>
@stop

@section('content')
    <div class="container">
        <div class="card mb-5 shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Entradas por mês</h4>
            </div>
            <div class="card-body">
                <canvas id="entradasChart" height="100"></canvas>
            </div>
        </div>
        <div class="card shadow">
            <div class="card-header bg-danger text-white text-center">
                <h4 class="mb-0">Saídas por mês</h4>
            </div>
            <div class="card-body">
                <canvas id="saidasChart" height="100"></canvas>
            </div>
        </div>
    </div>
@stop

@section('css')
  
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const entradasData = @json($entradas);
        const saidasData = @json($saidas);

        const entradasCtx = document.getElementById('entradasChart').getContext('2d');
        const saidasCtx = document.getElementById('saidasChart').getContext('2d');

        const entradasChart = new Chart(entradasCtx, {
            type: 'bar',
            data: {
                labels: entradasData.map(item => new Date(item.data).toLocaleString('pt-BR', { month: 'long' })),
                datasets: [{
                    label: 'Entradas',
                    data: entradasData.map(item => item.total),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const saidasChart = new Chart(saidasCtx, {
            type: 'bar',
            data: {
                labels: saidasData.map(item => new Date(item.data).toLocaleString('pt-BR', { month: 'long' })),
                datasets: [{
                    label: 'Saídas',
                    data: saidasData.map(item => item.total),
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@stop
