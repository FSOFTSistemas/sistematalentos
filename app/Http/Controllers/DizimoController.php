<?php

namespace App\Http\Controllers;

use App\Models\Dizimo;
use App\Models\Membro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DizimoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dizimos = Dizimo::with('membro')->orderBy('data', 'desc')->get();
        $membros = Membro::where('status', 'ativo')->orderBy('nome')->get();
        
        // Estatísticas para o mês atual
        $mesAtual = date('m');
        $anoAtual = date('Y');
        $totalDizimosMesAtual = Dizimo::where('mes_referencia', $mesAtual)
                                    ->where('ano_referencia', $anoAtual)
                                    ->sum('valor');
        
        $totalDizimistasMesAtual = Dizimo::where('mes_referencia', $mesAtual)
                                    ->where('ano_referencia', $anoAtual)
                                    ->distinct('membro_id')
                                    ->count('membro_id');
        
        return view('dizimos.index', compact('dizimos', 'membros', 'totalDizimosMesAtual', 'totalDizimistasMesAtual'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'membro_id' => 'required|exists:membros,id',
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
            'mes_referencia' => 'required|integer|min:1|max:12',
            'ano_referencia' => 'required|integer|min:2000|max:2100',
        ]);

        DB::beginTransaction();
        try {
            // Criar o registro de dízimo
            $dizimo = new Dizimo();
            $dizimo->membro_id = $request->membro_id;
            $dizimo->valor = $request->valor;
            $dizimo->data = $request->data;
            $dizimo->mes_referencia = $request->mes_referencia;
            $dizimo->ano_referencia = $request->ano_referencia;
            $dizimo->observacao = $request->observacao;
            $dizimo->user_id = Auth::id();
            $dizimo->save();
            
            // Criar o registro no caixa
            $membro = Membro::find($request->membro_id);
            $caixa = new \App\Models\Caixa();
            $caixa->descricao = "Dízimo de " . $membro->nome;
            $caixa->valor = $request->valor;
            $caixa->tipo = 'entrada';
            $caixa->data = $request->data;
            $caixa->categoria = 'Dízimo';
            $caixa->observacao = "Dízimo referente a " . $this->getNomeMes($request->mes_referencia) . "/" . $request->ano_referencia;
            $caixa->user_id = Auth::id();
            $caixa->save();
            
            // Vincular o dízimo ao registro de caixa
            $dizimo->caixa_id = $caixa->id;
            $dizimo->save();
            
            DB::commit();
            return redirect()->route('dizimos.index')->with('success', 'Dízimo registrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('dizimos.index')->with('error', 'Erro ao registrar dízimo: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Dizimo $dizimo)
    {
        return view('dizimos.show', compact('dizimo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dizimo $dizimo)
    {
        $request->validate([
            'membro_id' => 'required|exists:membros,id',
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
            'mes_referencia' => 'required|integer|min:1|max:12',
            'ano_referencia' => 'required|integer|min:2000|max:2100',
        ]);

        DB::beginTransaction();
        try {
            // Atualizar o registro de dízimo
            $dizimo->membro_id = $request->membro_id;
            $dizimo->valor = $request->valor;
            $dizimo->data = $request->data;
            $dizimo->mes_referencia = $request->mes_referencia;
            $dizimo->ano_referencia = $request->ano_referencia;
            $dizimo->observacao = $request->observacao;
            $dizimo->save();
            
            // Atualizar o registro no caixa, se existir
            if ($dizimo->caixa_id) {
                $caixa = \App\Models\Caixa::find($dizimo->caixa_id);
                if ($caixa) {
                    $membro = Membro::find($request->membro_id);
                    $caixa->descricao = "Dízimo de " . $membro->nome;
                    $caixa->valor = $request->valor;
                    $caixa->data = $request->data;
                    $caixa->observacao = "Dízimo referente a " . $this->getNomeMes($request->mes_referencia) . "/" . $request->ano_referencia;
                    $caixa->save();
                }
            }
            
            DB::commit();
            return redirect()->route('dizimos.index')->with('success', 'Dízimo atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('dizimos.index')->with('error', 'Erro ao atualizar dízimo: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dizimo $dizimo)
    {
        DB::beginTransaction();
        try {
            // Excluir o registro no caixa, se existir
            if ($dizimo->caixa_id) {
                $caixa = \App\Models\Caixa::find($dizimo->caixa_id);
                if ($caixa) {
                    $caixa->delete();
                }
            }
            
            // Excluir o registro de dízimo
            $dizimo->delete();
            
            DB::commit();
            return redirect()->route('dizimos.index')->with('success', 'Dízimo excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('dizimos.index')->with('error', 'Erro ao excluir dízimo: ' . $e->getMessage());
        }
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
