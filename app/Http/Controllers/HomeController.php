<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\Dizimo;
use App\Models\Membro;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $mesAtual = Carbon::now()->month;
        $empresaId = Auth::user()->empresa_id;
        // Estatísticas para o mês atual
        $mesAtual = date('m');
        $anoAtual = date('Y');

        // Pega o início e fim do mês atual
        $inicioMes = Carbon::now()->startOfMonth();
        $fimMes = Carbon::now()->endOfMonth();

        $totalEntradas = Caixa::where('empresa_id', $empresaId)
            ->where('tipo', 'entrada')
            ->whereBetween('created_at', [$inicioMes, $fimMes])
            ->sum('valor');

        $totalSaidas = Caixa::where('empresa_id', $empresaId)
            ->where('tipo', 'saida')
            ->whereBetween('created_at', [$inicioMes, $fimMes])
            ->sum('valor');

        // Saldo = entradas - saídas
        $saldoAtual = $totalEntradas - $totalSaidas;

        $totalDizimosMes = Dizimo::where('mes_referencia', (int) $mesAtual)
            ->where('ano_referencia', (int) $anoAtual)
            ->where('empresa_id', $empresaId)
            ->sum('valor');

        $totalDespesasMes = Caixa::where('tipo', 'saida')->where('empresa_id', $empresaId)->sum('valor');

        $totalMembros = Membro::count();
        $aniversariantes = Membro::whereMonth('data_nascimento', $mesAtual)->where('empresa_id', Auth::user()->empresa_id)->orderByRaw('DAY(data_nascimento) ASC')->get();

        return view('home', compact('totalMembros', 'aniversariantes', 'saldoAtual', 'totalDizimosMes', 'totalDespesasMes'));
    }
}
