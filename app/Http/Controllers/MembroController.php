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
        $empresa_id = Auth::user()->empresa_id;
        $membros = Membro::where('empresa_id', $empresa_id)->orderBy('nome')->get();
        return view('membros.index', compact('membros'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'status' => 'required|in:ativo,inativo',
                'email' => 'nullable|email|max:255',
                'telefone' => 'nullable|string|max:20',
                'cpf' => 'nullable|string|max:14',
                'data_nascimento' => 'nullable|date',
                'endereco' => 'nullable|string|max:255',
                'bairro' => 'nullable|string|max:255',
                'cidade' => 'nullable|string|max:255',
                'estado' => 'nullable|string|max:255',
                'cep' => 'nullable|string|max:10',
                'data_batismo' => 'nullable|date',
                'data_admissao' => 'nullable|date',
                'observacoes' => 'nullable|string',
                'nome_pai' => 'nullable|string|max:255',
                'nome_mae' => 'nullable|string|max:255',
                'conjuge' => 'nullable|string|max:255',
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
            $membro->nome_pai = $request->nome_pai;
            $membro->nome_mae = $request->nome_mae;
            $membro->conjuge = $request->conjuge;
            $membro->save();

            return redirect()->route('membros.index')->with('success', 'Membro cadastrado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with('error', $e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao cadastrar membro: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function create()
    {
        try {
            return view('membros._form');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    public function edit(Membro $membro)
    {
        try {
            return view('membros._form', compact('membro'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Membro $membro)
    {
        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'status' => 'required|in:ativo,inativo',
                'email' => 'nullable|email|max:255',
                'telefone' => 'nullable|string|max:20',
                'cpf' => 'nullable|string|max:14',
                'data_nascimento' => 'nullable|date',
                'endereco' => 'nullable|string|max:255',
                'bairro' => 'nullable|string|max:255',
                'cidade' => 'nullable|string|max:255',
                'estado' => 'nullable|string|max:255',
                'cep' => 'nullable|string|max:10',
                'data_batismo' => 'nullable|date',
                'data_admissao' => 'nullable|date',
                'observacoes' => 'nullable|string',
                'nome_pai' => 'nullable|string|max:255',
                'nome_mae' => 'nullable|string|max:255',
                'conjuge' => 'nullable|string|max:255',
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
            $membro->nome_pai = $request->nome_pai;
            $membro->nome_mae = $request->nome_mae;
            $membro->conjuge = $request->conjuge;
            $membro->save();

            return redirect()->route('membros.index')->with('success', 'Membro atualizado com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with('error', $e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar membro: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membro $membro)
    {
        try {
            $membro->delete();
            return redirect()->route('membros.index')->with('success', 'Membro excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir membro: ' . $e->getMessage());
        }
    }
}
