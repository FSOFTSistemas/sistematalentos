<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caixa;
use App\Models\Membro;
use App\Models\Dizimo;
use App\Models\Despesa;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    /**
     * Relatório mensal de entradas e saídas
     */
    public function mensal(Request $request)
    {
        $mes = $request->mes ?? date('m');
        $ano = $request->ano ?? date('Y');
        
        $entradas = Caixa::where('tipo', 'entrada')
                    ->whereMonth('data', $mes)
                    ->whereYear('data', $ano)
                    ->orderBy('data')
                    ->get();
                    
        $saidas = Caixa::where('tipo', 'saida')
                    ->whereMonth('data', $mes)
                    ->whereYear('data', $ano)
                    ->orderBy('data')
                    ->get();
                    
        $totalEntradas = $entradas->sum('valor');
        $totalSaidas = $saidas->sum('valor');
        $saldo = $totalEntradas - $totalSaidas;
        
        // Agrupar por categoria
        $entradasPorCategoria = $entradas->groupBy('categoria')
                                ->map(function ($item) {
                                    return $item->sum('valor');
                                });
                                
        $saidasPorCategoria = $saidas->groupBy('categoria')
                                ->map(function ($item) {
                                    return $item->sum('valor');
                                });
        
        return view('relatorios.mensal', compact(
            'entradas', 
            'saidas', 
            'totalEntradas', 
            'totalSaidas', 
            'saldo', 
            'entradasPorCategoria', 
            'saidasPorCategoria',
            'mes',
            'ano'
        ));
    }
    
    /**
     * Relatório de dizimistas com status de pagamento por mês
     */
    public function dizimistas(Request $request)
    {
        $mes = $request->mes ?? date('m');
        $ano = $request->ano ?? date('Y');
        
        // Buscar todos os membros ativos
        $membros = Membro::where('status', 'ativo')->orderBy('nome')->get();
        
        // Buscar dízimos do mês/ano selecionado
        $dizimos = Dizimo::where('mes_referencia', $mes)
                    ->where('ano_referencia', $ano)
                    ->get()
                    ->keyBy('membro_id');
        
        // Estatísticas
        $totalDizimistas = $dizimos->count();
        $totalMembros = $membros->count();
        $percentualDizimistas = $totalMembros > 0 ? ($totalDizimistas / $totalMembros) * 100 : 0;
        $totalValorDizimos = $dizimos->sum('valor');
        $mediaPorDizimista = $totalDizimistas > 0 ? $totalValorDizimos / $totalDizimistas : 0;
        
        return view('relatorios.dizimistas', compact(
            'membros', 
            'dizimos', 
            'mes', 
            'ano', 
            'totalDizimistas', 
            'totalMembros', 
            'percentualDizimistas',
            'totalValorDizimos',
            'mediaPorDizimista'
        ));
    }
    
    /**
     * Relatório de balanço financeiro da igreja
     */
    public function balanco(Request $request)
    {
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
                        ->sum('valor');
                        
            $saidas = Caixa::where('tipo', 'saida')
                        ->whereMonth('data', $mes)
                        ->whereYear('data', $ano)
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
                                ->groupBy('categoria')
                                ->orderBy('total', 'desc')
                                ->get();
                                
        $saidasPorCategoria = Caixa::where('tipo', 'saida')
                                ->whereYear('data', $ano)
                                ->selectRaw('categoria, sum(valor) as total')
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
}
