<?php

namespace App\Http\Controllers;

use App\Models\Membro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $membros = Membro::orderBy('nome')->get();
        return view('membros.index', compact('membros'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'status' => 'required|in:ativo,inativo',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14',
        ]);

        $membro = new Membro();
        $membro->nome = $request->nome;
        $membro->email = $request->email;
        $membro->telefone = $request->telefone;
        $membro->cpf = $request->cpf;
        $membro->data_nascimento = $request->data_nascimento;
        $membro->endereco = $request->endereco;
        $membro->bairro = $request->bairro;
        $membro->cidade = $request->cidade;
        $membro->estado = $request->estado;
        $membro->cep = $request->cep;
        $membro->status = $request->status;
        $membro->data_batismo = $request->data_batismo;
        $membro->data_admissao = $request->data_admissao;
        $membro->observacoes = $request->observacoes;
        $membro->empresa_id = Auth::user()->empresa_id;
        $membro->save();

        return redirect()->route('membros.index')->with('success', 'Membro cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function create()
    {
        return view('membros._form');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Membro $membro)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'status' => 'required|in:ativo,inativo',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14',
        ]);

        $membro->nome = $request->nome;
        $membro->email = $request->email;
        $membro->telefone = $request->telefone;
        $membro->cpf = $request->cpf;
        $membro->data_nascimento = $request->data_nascimento;
        $membro->endereco = $request->endereco;
        $membro->bairro = $request->bairro;
        $membro->cidade = $request->cidade;
        $membro->estado = $request->estado;
        $membro->cep = $request->cep;
        $membro->status = $request->status;
        $membro->data_batismo = $request->data_batismo;
        $membro->data_admissao = $request->data_admissao;
        $membro->observacoes = $request->observacoes;
        $membro->save();

        return redirect()->route('membros.index')->with('success', 'Membro atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membro $membro)
    {
        $membro->delete();
        return redirect()->route('membros.index')->with('success', 'Membro excluído com sucesso!');
    }
}
