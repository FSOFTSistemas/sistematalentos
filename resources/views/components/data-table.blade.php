@php
    $uniqueId = 'myTable_' . uniqid();
    $sortColumnIndex = isset($sortColumnIndex) ? $sortColumnIndex : 0;  // Padrão: ordenar pela primeira coluna
    $sortDirection = isset($sortDirection) ? $sortDirection : 'asc';  // Padrão: ordenação crescente
@endphp

<table id="{{ $uniqueId }}" class="w-100">
    {{ $slot }}
    @if (isset($showTotal) && $showTotal)
        <tfoot>
            <tr>
                <td colspan="4"><strong>Total</strong></td>
                <td id="total"><strong>R$ 0,00</strong></td>
                <td></td>
            </tr>
        </tfoot>
    @endif
</table>

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link
        href="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.0.3/af-2.7.0/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/cr-2.0.0/fc-5.0.0/fh-4.0.1/kt-2.12.0/r-3.0.1/sc-2.4.1/sb-1.7.0/sp-2.3.0/datatables.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
         table#{{ $uniqueId }} th,
        table#{{ $uniqueId }} td {
            padding: 1px;
            text-align: center;
        }
        table#{{ $uniqueId }} {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            font-size: 14px;
            color: #fff;
            background-color: #1E3A5F;
        }
    
        table#{{ $uniqueId }} thead {
            background-color: var(--blue-1) !important;
        }
    
        table#{{ $uniqueId }} thead th {
            padding: 10px;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            text-transform: uppercase;
            font-size: 12px;
            color: #f5f5f5;
        }
    
        table#{{ $uniqueId }} tbody td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
            color: #000000;
        }
    
        table#{{ $uniqueId }} tbody tr:nth-child(odd) {
            background-color: #f6f6f6;
        }
    
        table#{{ $uniqueId }} tbody tr:nth-child(even) {
            background-color: #f6f6f6;
        }
    
        table#{{ $uniqueId }} tfoot td {
            font-weight: bold;
            padding: 10px;
            background-color: #f8f9fa;
            border-top: 2px solid #dee2e6;
        }
    
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 5px;
            font-size: 14px;
            color: #495057;
        }
    
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 5px 10px;
            margin: 2px;
            border-radius: 4px;
            border: 1px solid transparent;
            background-color: #e9ecef;
            color: #49574d;
            font-size: 13px;
        }
    
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #0d6efd;
            color: #fff !important;
            border-color: #0d6efd;
        }
    
        .dt-buttons .dt-button {
            border-radius: 4px !important;
            border: none !important;
            background-color: #fff !important;
            color: var(--blue-1) !important;
            margin-left: 5px !important;
            padding: 5px 10px;
            transition: 0.5s;
        }
    
        .dt-buttons .dt-button:hover {
            color: var(--blue-2) !important;
            transition: all 0.5s;
        }
        .dt-search label, .dt-search input {
            color: var(--green-2);
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/dt/jq-3.7.0/jszip-3.10.1/dt-2.0.3/af-2.7.0/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/cr-2.0.0/fc-5.0.0/fh-4.0.1/kt-2.12.0/r-3.0.1/sc-2.4.1/sb-1.7.0/sp-2.3.0/datatables.min.js">
    </script>

    <script>
        var valueColumnIndex = {{ $valueColumnIndex }};
        var sortColumnIndex = {{ $sortColumnIndex }};  // Índice da coluna para ordenação
        var sortDirection = '{{ $sortDirection }}';  // Direção de ordenação

        var table = $('#{{ $uniqueId }}').DataTable({
            responsive: true,
            pageLength: {{ $itemsPerPage }},
            columnDefs: {{ Js::from($responsive) }},
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
            },
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copyHtml5',
                    text: '<i class="fas fa-copy"></i>', // Ícone de copiar
                    titleAttr: 'Copiar para a área de transferência',
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i>', // Ícone de Excel
                    titleAttr: 'Exportar para Excel',
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fas fa-file-csv"></i>', // Ícone de CSV
                    titleAttr: 'Exportar para CSV',
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i>', // Ícone de PDF
                    titleAttr: 'Exportar para PDF',
                    orientation: 'landscape',
                    pageSize: 'A4'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i>', // Ícone de Impressora
                    titleAttr: 'Imprimir tabela',
                }
            ],
            order: [[sortColumnIndex, sortDirection]],  // Adiciona a ordenação dinâmica aqui
            initComplete: function() {
                // Ajusta o tamanho da fonte e o alinhamento
                $('#{{ $uniqueId }}').css('font-size', '14px');
                $('#{{ $uniqueId }} th, #{{ $uniqueId }} td').css('font-size', '14px');

                // Alinha os botões à direita
                $('.dt-buttons').css({
                    'float': 'right',
                    'margin-top': '10px' // Um pequeno espaço entre a tabela e os botões
                });

                // Ajusta o tamanho dos botões
                $('.dt-button').css('font-size', '14px'); // Ajuste o tamanho da fonte para os botões
                $('.dt-button').addClass('btn-sm'); // Tamanho pequeno dos botões (Bootstrap)
            }
        });

        function calculateTotal() {
            console.log("Calculando o total...");
            var total = 0;

            table.rows({
                filter: 'applied'
            }).every(function() {
                var data = this.data();
                var value = data[valueColumnIndex];
                if (typeof value === 'string') {
                    value = value.replace(/\./g, '').replace(',', '.');
                }
                value = parseFloat(value);
                if (!isNaN(value)) {
                    total += value;
                }
            });

            $('#total').html('<strong>' + total.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + '</strong>');
        }

        table.on('draw', function() {
            console.log("Evento draw chamado");
            calculateTotal();
        });

        table.on('search', function() {
            console.log("Evento search chamado");
            calculateTotal();
        });

        table.on('init', function() {
            console.log("Tabela inicializada");
            calculateTotal();
        });
    </script>
@endsection