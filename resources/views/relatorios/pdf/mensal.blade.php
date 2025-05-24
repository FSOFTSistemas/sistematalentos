<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Relatório Mensal de Entradas e Saídas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #3c8dbc;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-box {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .info-box.success {
            background-color: #dff0d8;
            border-color: #d6e9c6;
        }
        .info-box.danger {
            background-color: #f2dede;
            border-color: #ebccd1;
        }
        .info-box h3 {
            margin-top: 0;
            font-size: 18px;
        }
        .info-box p {
            margin-bottom: 0;
            font-size: 20px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            color: white;
        }
        .badge-primary {
            background-color: #3c8dbc;
        }
        .badge-success {
            background-color: #00a65a;
        }
        .badge-info {
            background-color: #00c0ef;
        }
        .badge-secondary {
            background-color: #777;
        }
        .badge-warning {
            background-color: #f39c12;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding-right: 15px;
            padding-left: 15px;
            box-sizing: border-box;
        }
        @media print {
            body {
                padding: 0;
                font-size: 12px;
            }
            .header h1 {
                font-size: 20px;
            }
            .info-box p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Mensal de Entradas e Saídas</h1>
        <p>{{ $igreja_nome ?? 'Ecclesia - Sistema de Gestão para Igrejas' }}</p>
        <p>Período: {{ $periodo }}</p>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="info-box success">
                <h3>Total de Entradas</h3>
                <p>R$ {{ number_format($total_entradas ?? 0, 2, ',', '.') }}</p>
            </div>
        </div>
        <div class="col-6">
            <div class="info-box danger">
                <h3>Total de Saídas</h3>
                <p>R$ {{ number_format($total_saidas ?? 0, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <h3>Entradas</h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($entradas ?? [] as $entrada)
            <tr>
                <td>{{ \Carbon\Carbon::parse($entrada->data)->format('d/m/Y') }}</td>
                <td>{{ $entrada->descricao }}</td>
                <td>
                    <span class="badge 
                        @if($entrada->categoria == 'dizimo') badge-primary 
                        @elseif($entrada->categoria == 'oferta') badge-success 
                        @elseif($entrada->categoria == 'doacao') badge-info 
                        @else badge-secondary 
                        @endif">
                        {{ ucfirst($entrada->categoria) }}
                    </span>
                </td>
                <td>R$ {{ number_format($entrada->valor, 2, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Nenhuma entrada encontrada</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Saídas</h3>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($saidas ?? [] as $saida)
            <tr>
                <td>{{ \Carbon\Carbon::parse($saida->data)->format('d/m/Y') }}</td>
                <td>{{ $saida->descricao }}</td>
                <td>
                    <span class="badge 
                        @if($saida->categoria == 'administrativa') badge-warning 
                        @elseif($saida->categoria == 'manutencao') badge-info 
                        @elseif($saida->categoria == 'eventos') badge-primary 
                        @else badge-secondary 
                        @endif">
                        {{ ucfirst($saida->categoria) }}
                    </span>
                </td>
                <td>R$ {{ number_format($saida->valor, 2, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Nenhuma saída encontrada</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Relatório gerado em {{ date('d/m/Y H:i:s') }}</p>
        <p>Ecclesia - Sistema de Gestão para Igrejas</p>
    </div>
</body>
</html>
