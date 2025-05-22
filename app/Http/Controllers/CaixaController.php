<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaixaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movimentacoes = Caixa::orderBy('data', 'desc')->get();
        $totalEntradas = Caixa::where('tipo', 'entrada')->sum('valor');
        $totalSaidas = Caixa::where('tipo', 'saida')->sum('valor');
        
        return view('caixa.index', compact('movimentacoes', 'totalEntradas', 'totalSaidas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:entrada,saida',
            'data' => 'required|date',
            'categoria' => 'required|string|max:100',
        ]);

        $caixa = new Caixa();
        $caixa->descricao = $request->descricao;
        $caixa->valor = $request->valor;
        $caixa->tipo = $request->tipo;
        $caixa->data = $request->data;
        $caixa->categoria = $request->categoria;
        $caixa->observacao = $request->observacao;
        $caixa->user_id = Auth::id();
        $caixa->save();

        return redirect()->route('caixa.index')->with('success', 'Movimentação registrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Caixa $caixa)
    {
        return view('caixa.show', compact('caixa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caixa $caixa)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:entrada,saida',
            'data' => 'required|date',
            'categoria' => 'required|string|max:100',
        ]);

        $caixa->descricao = $request->descricao;
        $caixa->valor = $request->valor;
        $caixa->tipo = $request->tipo;
        $caixa->data = $request->data;
        $caixa->categoria = $request->categoria;
        $caixa->observacao = $request->observacao;
        $caixa->save();

        return redirect()->route('caixa.index')->with('success', 'Movimentação atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caixa $caixa)
    {
        $caixa->delete();
        return redirect()->route('caixa.index')->with('success', 'Movimentação excluída com sucesso!');
    }
}
