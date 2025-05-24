
// Arquivo de integração para relatórios
$(function () {
    // Inicialização das DataTables para todas as tabelas com a classe datatable
    $('.datatable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
        },
        "buttons": [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: ':visible:not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: ':visible:not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimir',
                className: 'btn btn-info btn-sm',
                exportOptions: {
                    columns: ':visible:not(:last-child)'
                }
            }
        ],
        "dom": 'Bfrtip'
    });

    // Máscaras para campos de formulário
    if ($.fn.inputmask) {
        $('.money').inputmask('currency', {
            radixPoint: ',',
            groupSeparator: '.',
            allowMinus: false,
            prefix: 'R$ ',
            digits: 2,
            digitsOptional: false,
            rightAlign: false,
            unmaskAsNumber: true
        });
    }

    // Atualização automática de datas baseada no período selecionado
    $('#periodo').change(function() {
        atualizarDatasPeriodo();
    });

    function atualizarDatasPeriodo() {
        var periodo = $('#periodo').val();
        var hoje = new Date();
        var dataInicio = new Date();
        var dataFim = new Date();
        
        switch(periodo) {
            case 'mensal':
                dataInicio = new Date(hoje.getFullYear(), hoje.getMonth(), 1);
                dataFim = new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0);
                break;
            case 'trimestral':
                dataInicio = new Date(hoje.getFullYear(), Math.floor(hoje.getMonth() / 3) * 3, 1);
                dataFim = new Date(hoje.getFullYear(), Math.floor(hoje.getMonth() / 3) * 3 + 3, 0);
                break;
            case 'semestral':
                dataInicio = new Date(hoje.getFullYear(), Math.floor(hoje.getMonth() / 6) * 6, 1);
                dataFim = new Date(hoje.getFullYear(), Math.floor(hoje.getMonth() / 6) * 6 + 6, 0);
                break;
            case 'anual':
                dataInicio = new Date(hoje.getFullYear(), 0, 1);
                dataFim = new Date(hoje.getFullYear(), 12, 0);
                break;
        }
        
        $('#data_inicio').val(formatarData(dataInicio));
        $('#data_fim').val(formatarData(dataFim));
    }

    function formatarData(data) {
        var ano = data.getFullYear();
        var mes = (data.getMonth() + 1).toString().padStart(2, '0');
        var dia = data.getDate().toString().padStart(2, '0');
        return ano + '-' + mes + '-' + dia;
    }

    // Inicializar datas se o seletor de período existir
    if ($('#periodo').length) {
        atualizarDatasPeriodo();
    }

    // Feedback de sucesso ou erro com SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    // Validação de formulários
    $('.needs-validation').submit(function(event) {
        if (this.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        $(this).addClass('was-validated');
    });

    // Configuração responsiva para gráficos
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;

    // Função para exportar gráficos como imagem
    $('.btn-export-chart').click(function() {
        var chartId = $(this).data('chart');
        var canvas = document.getElementById(chartId);
        
        if (canvas) {
            // Converter canvas para imagem
            var image = canvas.toDataURL('image/png');
            
            // Criar link para download
            var link = document.createElement('a');
            link.download = 'grafico-' + chartId + '.png';
            link.href = image;
            link.click();
        }
    });

    // Função para imprimir relatório atual
    $('.btn-print-report').click(function() {
        window.print();
    });
});
