<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Relatório de Dizimistas</title>
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
        .info-box.primary {
            background-color: #d9edf7;
            border-color: #bce8f1;
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
        .badge-success {
            background-color: #00a65a;
        }
        .badge-danger {
            background-color: #dd4b39;
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
        .col-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
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
        <h1>Relatório de Dizimistas</h1>
        <p>{{ $igreja_nome ?? 'Ecclesia - Sistema de Gestão para Igrejas' }}</p>
        <p>Período: {{ $periodo }}</p>
    </div>

    <div class="row">
        <div class="col-4">
            <div class="info-box primary">
                <h3>Total de Membros</h3>
                <p>{{ $total_membros ?? 0 }}</p>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box success">
                <h3>Dízimos Pagos</h3>
                <p>{{ $total_pagos ?? 0 }}</p>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box danger">
                <h3>Dízimos Pendentes</h3>
                <p>{{ $total_pendentes ?? 0 }}</p>
            </div>
        </div>
    </div>

    <h3>Lista de Dizimistas</h3>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Contato</th>
                <th>Status</th>
                <th>Valor</th>
                <th>Data de Pagamento</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dizimistas ?? [] as $dizimista)
            <tr>
                <td>{{ $dizimista->membro->nome ?? 'N/A' }}</td>
                <td>{{ $dizimista->membro->telefone ?? 'N/A' }}</td>
                <td>
                    @if(isset($dizimista->status) && $dizimista->status == 'pago')
                        <span class="badge badge-success">Pago</span>
                    @else
                        <span class="badge badge-danger">Pendente</span>
                    @endif
                </td>
                <td>
                    @if(isset($dizimista->valor))
                        R$ {{ number_format($dizimista->valor, 2, ',', '.') }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if(isset($dizimista->data_pagamento))
                        {{ \Carbon\Carbon::parse($dizimista->data_pagamento)->format('d/m/Y') }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Nenhum dizimista encontrado</td>
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
