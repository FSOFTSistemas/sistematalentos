<?php

namespace App\Http\Controllers;

use App\Models\Plano;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanoController extends Controller
{
    /**
     * Constructor para aplicar middleware de restrição de acesso
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:master']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $planos = Plano::orderBy('valor')->get();
        return view('planos.index', compact('planos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('planos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'limite_membros' => 'required|integer|min:1',
            'periodo' => 'required|in:mensal,trimestral,semestral,anual',
            'descricao' => 'nullable|string',
        ]);

        $plano = new Plano();
        $plano->nome = $request->nome;
        $plano->valor = $request->valor;
        $plano->limite_membros = $request->limite_membros;
        $plano->periodo = $request->periodo;
        $plano->descricao = $request->descricao;
        $plano->ativo = $request->has('ativo');
        $plano->save();

        return redirect()->route('planos.index')->with('success', 'Plano criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Plano $plano)
    {
        return view('planos.show', compact('plano'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plano $plano)
    {
        return view('planos.edit', compact('plano'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plano $plano)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'limite_membros' => 'required|integer|min:1',
            'periodo' => 'required|in:mensal,trimestral,semestral,anual',
            'descricao' => 'nullable|string',
        ]);

        $plano->nome = $request->nome;
        $plano->valor = $request->valor;
        $plano->limite_membros = $request->limite_membros;
        $plano->periodo = $request->periodo;
        $plano->descricao = $request->descricao;
        $plano->ativo = $request->has('ativo');
        $plano->save();

        return redirect()->route('planos.index')->with('success', 'Plano atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plano $plano)
    {
        // Verificar se existem empresas usando este plano
        if ($plano->empresas()->count() > 0) {
            return redirect()->route('planos.index')->with('error', 'Este plano não pode ser excluído porque está sendo usado por uma ou mais empresas.');
        }
        
        $plano->delete();
        return redirect()->route('planos.index')->with('success', 'Plano excluído com sucesso!');
    }
}
