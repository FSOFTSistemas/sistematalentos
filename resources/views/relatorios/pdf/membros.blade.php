<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Membros</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 5mm 5mm 5mm 5mm;
            /* top right bottom left */
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        .header {
            border-bottom: 2px solid #4A90E2;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #4A90E2;
        }

        .header .sub {
            font-size: 8px;
            color: #666;
            text-align: right;
        }

        .header .titulo {
            font-size: 18px;
            color: #082f4f;
            font-family: 'Roboto', sans-serif;
            font-weight: bold;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            border-left: none;
            border-right: none;
            padding: 6px;
            text-align: left;
            font-size: 11px;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>{{ Auth::user()->empresa->nome ?? 'Minha Empresa' }}</h1>
        <div class="titulo">Relatório de Membros</div>
        <div class="sub">
            Data de Impressão: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Endereço</th>
                <th>Data Batismo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($membros as $membro)
                <tr>
                    <td>{{ $membro->id }}</td>
                    <td>{{ $membro->nome }}</td>
                    <td>{{ $membro->telefone }}</td>
                    <td>{{ $membro->endereco }}</td>
                    <td>{{ $membro->data_batismo ? \Carbon\Carbon::parse($membro->data_batismo)->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $membro->status === 'ativo' ? 'Comungante' : 'Inativo' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div
        style="position: fixed; bottom: 10px; right: 20px; font-size: 8px; color: #666; max-width: 300px; text-align: right;">
        ..::Impresso no sistema Ecclesia::..
    </div>
</body>

</html>
