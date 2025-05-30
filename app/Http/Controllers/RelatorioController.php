<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caixa;
use App\Models\Membro;
use App\Models\Dizimo;
use App\Models\Despesa;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioController extends Controller
{

    public function index()
    {
        return view('relatorios.index');
    }
    /**
     * Relatório mensal de entradas e saídas
     */
    public function mensal(Request $request)
    {
        // Parâmetros do filtro
        $mes = $request->input('mes', date('m'));
        $ano = $request->input('ano', date('Y'));
        $categoria = $request->input('categoria');

        // Datas de início e fim do mês
        $dataInicio = Carbon::createFromDate($ano, $mes, 1)->startOfDay();
        $dataFim = Carbon::createFromDate($ano, $mes, 1)->endOfMonth()->endOfDay();
        // Consulta base para entradas
        $queryEntradas = Caixa::where('tipo', 'entrada')
        ->whereBetween('data', [$dataInicio, $dataFim]);
        
        // Consulta base para saídas
        $querySaidas = Caixa::where('tipo', 'saida')
        ->whereBetween('data', [$dataInicio, $dataFim]);
        
        // Filtro por categoria se especificado
        if ($categoria) {
            $queryEntradas->where('categoria', $categoria);
            $querySaidas->where('categoria', $categoria);
        }
        
        // Executar consultas
        $entradas = $queryEntradas->orderBy('data')->get();
        $saidas = $querySaidas->orderBy('data')->get();
        
   
        // Calcular totais
        $total_entradas = $entradas->sum('valor');
        $total_saidas = $saidas->sum('valor');

        // Preparar dados para o gráfico
        $dias = [];
        $valores_entradas = [];
        $valores_saidas = [];

        // Criar período com todos os dias do mês
        $periodo = CarbonPeriod::create($dataInicio, $dataFim);
        
        foreach ($periodo as $data) {
            $dia = $data->format('d');
            $dias[] = $dia;

            // Somar valores para cada dia
            $valor_entrada_dia = $entradas->filter(function ($item) use ($data) {
                return Carbon::parse($item->data)->format('Y-m-d') === $data->format('Y-m-d');
            })->sum('valor');

            

            $valor_saida_dia = $saidas->filter(function ($item) use ($data) {
                return Carbon::parse($item->data)->format('Y-m-d') === $data->format('Y-m-d');
            })->sum('valor');

            $valores_entradas[] = $valor_entrada_dia;
            $valores_saidas[] = $valor_saida_dia;
        }

        
        // Formatar período para exibição
        $periodo = $this->getNomeMes($mes) . '/' . $ano;

        return view('relatorios.mensal', compact(
            'entradas',
            'saidas',
            'total_entradas',
            'total_saidas',
            'dias',
            'valores_entradas',
            'valores_saidas',
            'mes',
            'ano',
            'categoria',
            'periodo'
        ));
    }

    /**
     * Exportar relatório mensal para PDF
     */
    public function mensalPdf(Request $request)
    {
        // Parâmetros do filtro
        $mes = $request->input('mes', date('m'));
        $ano = $request->input('ano', date('Y'));
        $categoria = $request->input('categoria');

        // Datas de início e fim do mês
        $dataInicio = Carbon::createFromDate($ano, $mes, 1)->startOfDay();
        $dataFim = Carbon::createFromDate($ano, $mes, 1)->endOfMonth()->endOfDay();

        // Consulta base para entradas
        $queryEntradas = Caixa::where('tipo', 'entrada')
            ->whereBetween('data', [$dataInicio, $dataFim]);

        // Consulta base para saídas
        $querySaidas = Caixa::where('tipo', 'saida')
            ->whereBetween('data', [$dataInicio, $dataFim]);

        // Filtro por categoria se especificado
        if ($categoria) {
            $queryEntradas->where('categoria', $categoria);
            $querySaidas->where('categoria', $categoria);
        }

        // Executar consultas
        $entradas = $queryEntradas->orderBy('data')->get();
        $saidas = $querySaidas->orderBy('data')->get();

        // Calcular totais
        $total_entradas = $entradas->sum('valor');
        $total_saidas = $saidas->sum('valor');

        // Formatar período para exibição
        $periodo = $this->getNomeMes($mes) . '/' . $ano;

        // Configurar PDF
        $pdf = Pdf::loadView('relatorios.pdf.mensal', compact(
            'entradas',
            'saidas',
            'total_entradas',
            'total_saidas',
            'periodo'
        ));

        // Nome do arquivo
        $filename = 'relatorio_mensal_' . $mes . '_' . $ano . '.pdf';

        // Retornar PDF para download
        return $pdf->download($filename);
    }

    /**
     * Exportar relatório mensal para Excel
     */
    public function mensalExcel(Request $request)
    {
        // Parâmetros do filtro
        $mes = $request->input('mes', date('m'));
        $ano = $request->input('ano', date('Y'));
        $categoria = $request->input('categoria');

        // Nome do arquivo
        $filename = 'relatorio_mensal_' . $mes . '_' . $ano . '.xlsx';

        // Retornar Excel para download
        return Excel::download(new MensalExport($mes, $ano, $categoria), $filename);
    }

    /**
     * Relatório de dizimistas com status de pagamento
     */
    public function dizimistas(Request $request)
    {
        // Parâmetros do filtro
        $mes = $request->input('mes', date('m'));
        $ano = $request->input('ano', date('Y'));
        $status = $request->input('status');

        // Buscar todos os membros
        $membros = Membro::where('status', 'ativo')->get();
        $total_membros = $membros->count();

        // Buscar dízimos pagos no mês/ano especificado
        $dizimos_pagos = Dizimo::where('mes_referencia', $mes)
            ->where('ano_referencia', $ano)
            ->with('membro', 'user')
            ->get();

        // IDs dos membros que pagaram
        $ids_pagos = $dizimos_pagos->pluck('membro_id')->toArray();

        // Membros que não pagaram
        $membros_pendentes = $membros->whereNotIn('id', $ids_pagos);

        // Preparar lista de dizimistas com status
        $dizimistas = collect();

        // Adicionar membros que pagaram
        foreach ($dizimos_pagos as $dizimo) {
            $dizimista = (object)[
                'membro' => $dizimo->membro,
                'status' => 'pago',
                'valor' => $dizimo->valor,
                'data_pagamento' => $dizimo->data,
                'id' => $dizimo->id
            ];
            $dizimistas->push($dizimista);
        }

        // Adicionar membros pendentes
        foreach ($membros_pendentes as $membro) {
            $dizimista = (object)[
                'membro' => $membro,
                'status' => 'pendente',
                'valor' => null,
                'data_pagamento' => null
            ];
            $dizimistas->push($dizimista);
        }

        // Filtrar por status se especificado
        if ($status) {
            $dizimistas = $dizimistas->where('status', $status);
        }

        // Contagem de pagos e pendentes
        $total_pagos = $dizimistas->where('status', 'pago')->count();
        $total_pendentes = $dizimistas->where('status', 'pendente')->count();

        // Dados para gráfico de histórico
        $meses_historico = [];
        $valores_historico = [];

        // Buscar histórico dos últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $data_historico = Carbon::createFromDate($ano, $mes, 1)->subMonths($i);
            $mes_historico = $data_historico->format('m');
            $ano_historico = $data_historico->format('Y');

            $meses_historico[] = $this->getNomeMes($mes_historico) . '/' . $ano_historico;

            $pagos_historico = Dizimo::where('mes_referencia', $mes_historico)
                ->where('ano_referencia', $ano_historico)
                ->count();

            $valores_historico[] = $pagos_historico;
        }

        // Formatar período para exibição
        $periodo = $this->getNomeMes($mes) . '/' . $ano;

        return view('relatorios.dizimistas', compact(
            'dizimistas',
            'membros_pendentes',
            'dizimos_pagos',
            'total_membros',
            'total_pagos',
            'total_pendentes',
            'meses_historico',
            'valores_historico',
            'mes',
            'ano',
            'status',
            'periodo'
        ));
    }

    /**
     * Exportar relatório de dizimistas para PDF
     */
    public function dizimistasPdf(Request $request)
    {
        // Parâmetros do filtro
        $mes = $request->input('mes', date('m'));
        $ano = $request->input('ano', date('Y'));
        $status = $request->input('status');

        // Buscar todos os membros
        $membros = Membro::where('status', 'ativo')->get();
        $total_membros = $membros->count();

        // Buscar dízimos pagos no mês/ano especificado
        $dizimos_pagos = Dizimo::where('mes_referencia', $mes)
            ->where('ano_referencia', $ano)
            ->with('membro', 'user')
            ->get();

        // IDs dos membros que pagaram
        $ids_pagos = $dizimos_pagos->pluck('membro_id')->toArray();

        // Membros que não pagaram
        $membros_pendentes = $membros->whereNotIn('id', $ids_pagos);

        // Preparar lista de dizimistas com status
        $dizimistas = collect();

        // Adicionar membros que pagaram
        foreach ($dizimos_pagos as $dizimo) {
            $dizimista = (object)[
                'membro' => $dizimo->membro,
                'status' => 'pago',
                'valor' => $dizimo->valor,
                'data_pagamento' => $dizimo->data,
                'id' => $dizimo->id
            ];
            $dizimistas->push($dizimista);
        }

        // Adicionar membros pendentes
        foreach ($membros_pendentes as $membro) {
            $dizimista = (object)[
                'membro' => $membro,
                'status' => 'pendente',
                'valor' => null,
                'data_pagamento' => null
            ];
            $dizimistas->push($dizimista);
        }

        // Filtrar por status se especificado
        if ($status) {
            $dizimistas = $dizimistas->where('status', $status);
        }

        // Contagem de pagos e pendentes
        $total_pagos = $dizimistas->where('status', 'pago')->count();
        $total_pendentes = $dizimistas->where('status', 'pendente')->count();

        // Formatar período para exibição
        $periodo = $this->getNomeMes($mes) . '/' . $ano;

        // Configurar PDF
        $pdf = PDF::loadView('relatorios.pdf.dizimistas', compact(
            'dizimistas',
            'total_membros',
            'total_pagos',
            'total_pendentes',
            'periodo'
        ));

        // Nome do arquivo
        $filename = 'relatorio_dizimistas_' . $mes . '_' . $ano . '.pdf';

        // Retornar PDF para download
        return $pdf->download($filename);
    }

    /**
     * Exportar relatório de dizimistas para Excel
     */
    public function dizimistasExcel(Request $request)
    {
        // Parâmetros do filtro
        $mes = $request->input('mes', date('m'));
        $ano = $request->input('ano', date('Y'));
        $status = $request->input('status');

        // Nome do arquivo
        $filename = 'relatorio_dizimistas_' . $mes . '_' . $ano . '.xlsx';

        // Retornar Excel para download
        return Excel::download(new DizimistasExport($mes, $ano, $status), $filename);
    }
    /**
     * Relatório de balanço financeiro da igreja
     */
    public function balanco(Request $request)
    {
        $empresa_id = Auth::user()->empresa_id;
        $anoAtual = date('Y');
        $ano = $request->ano ?? $anoAtual;

        $meses = [];
        $entradasPorMes = [];
        $saidasPorMes = [];
        $saldoPorMes = [];

        // Dados para cada mês do ano
        for ($mes = 1; $mes <= 12; $mes++) {
            $meses[] = $this->getNomeMes($mes);

            $entradas = Caixa::where('tipo', 'entrada')
                ->whereMonth('data', $mes)
                ->whereYear('data', $ano)
                ->where('empresa_id', $empresa_id)
                ->sum('valor');

            $saidas = Caixa::where('tipo', 'saida')
                ->whereMonth('data', $mes)
                ->whereYear('data', $ano)
                ->where('empresa_id', $empresa_id)
                ->sum('valor');

            $entradasPorMes[] = $entradas;
            $saidasPorMes[] = $saidas;
            $saldoPorMes[] = $entradas - $saidas;
        }

        // Totais anuais
        $totalEntradasAno = array_sum($entradasPorMes);
        $totalSaidasAno = array_sum($saidasPorMes);
        $saldoAno = $totalEntradasAno - $totalSaidasAno;

        // Dados para gráficos
        $dadosGrafico = [
            'meses' => $meses,
            'entradas' => $entradasPorMes,
            'saidas' => $saidasPorMes,
            'saldo' => $saldoPorMes
        ];

        // Categorias de entradas e saídas para o ano
        $entradasPorCategoria = Caixa::where('tipo', 'entrada')
            ->whereYear('data', $ano)
            ->selectRaw('categoria, sum(valor) as total')
            ->where('empresa_id', $empresa_id)
            ->groupBy('categoria')
            ->orderBy('total', 'desc')
            ->get();

        $saidasPorCategoria = Caixa::where('tipo', 'saida')
            ->whereYear('data', $ano)
            ->selectRaw('categoria, sum(valor) as total')
            ->where('empresa_id', $empresa_id)
            ->groupBy('categoria')
            ->orderBy('total', 'desc')
            ->get();

        return view('relatorios.balanco', compact(
            'ano',
            'meses',
            'entradasPorMes',
            'saidasPorMes',
            'saldoPorMes',
            'totalEntradasAno',
            'totalSaidasAno',
            'saldoAno',
            'dadosGrafico',
            'entradasPorCategoria',
            'saidasPorCategoria'
        ));
    }

    /**
     * Retorna o nome do mês com base no número
     */
    private function getNomeMes($numero)
    {
        $meses = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        ];

        return $meses[$numero] ?? '';
    }

    public function graficos()
    {
        $empresa_id = Auth::user()->empresa_id;

        $entradas = Caixa::selectRaw('DATE(data) as data, SUM(valor) as total')
            ->where('tipo', 'entrada')
            ->where('empresa_id', $empresa_id)
            ->groupBy('data')
            ->orderBy('data')
            ->get();

        $saidas = Caixa::selectRaw('DATE(data) as data, SUM(valor) as total')
            ->where('tipo', 'saida')
            ->where('empresa_id', $empresa_id)
            ->groupBy('data')
            ->orderBy('data')
            ->get();

        return view('relatorios.graficos', compact('entradas', 'saidas'));
    }

}