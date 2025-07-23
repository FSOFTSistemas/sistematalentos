<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\Membro;
use App\Models\Dizimo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CaixaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresa_id = Auth::user()->empresa_id;
        $movimentacoes = Caixa::where('empresa_id', $empresa_id)->orderBy('data', 'desc')->get();
        $totalEntradas = Caixa::where('tipo', 'entrada')->where('empresa_id', $empresa_id)->sum('valor');
        $totalSaidas = Caixa::where('tipo', 'saida')->where('empresa_id', $empresa_id)->sum('valor');
        $membros = Membro::where('empresa_id', $empresa_id)->orderBy('nome')->get();

        return view('caixa.index', compact('movimentacoes', 'totalEntradas', 'totalSaidas', 'membros'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'valor' => 'required|numeric|min:0.01',
                'tipo' => 'required|in:entrada,saida',
                'data' => 'required|date',
                'categoriaEntrada' => $request->tipo === 'entrada' ? 'required|string|max:100' : 'nullable|string|max:100',
                'observacao' => 'nullable|string|max:1000',
                'membro_id' => ($request->categoriaEntrada ?? $request->categoria) === 'Dízimo' ? 'required|exists:membros,id' : 'nullable',
            ], [
                'valor.required' => 'O campo valor é obrigatório.',
                'valor.numeric' => 'O valor deve ser numérico.',
                'valor.min' => 'O valor deve ser no mínimo 0,01.',
                'tipo.required' => 'O campo tipo é obrigatório.',
                'tipo.in' => 'O tipo deve ser "entrada" ou "saida".',
                'data.required' => 'O campo data é obrigatório.',
                'data.date' => 'O campo data deve conter uma data válida.',
                'categoriaEntrada.required' => 'O campo categoriaEntrada é obrigatório.',
                'categoriaEntrada.string' => 'A categoriaEntrada deve ser um texto.',
                'categoriaEntrada.max' => 'A categoriaEntrada pode ter no máximo 100 caracteres.',
                'observacao.string' => 'A observação deve ser um texto.',
                'observacao.max' => 'A observação pode ter no máximo 1000 caracteres.',
                'membro_id.required' => 'O campo membro é obrigatório para categoriaEntrada Dízimo.',
                'membro_id.exists' => 'O membro selecionado é inválido.',
            ]);

            $caixa = new Caixa();
            $caixa->descricao = $request->descricao;
            $caixa->valor = $request->valor;
            $caixa->tipo = $request->tipo;
            $caixa->data = $request->data;
            $categoria = $request->categoriaEntrada ?? $request->categoria;
            $caixa->categoria = $categoria;
            $caixa->observacao = $request->observacao;
            $caixa->membro_id = $categoria === 'Dízimo' ? $request->membro_id : null;
            $caixa->user_id = Auth::id();
            $caixa->empresa_id = Auth::user()->empresa_id;
            $caixa->save();

            if ($categoria === 'Dízimo') {
                Dizimo::create([
                    'membro_id' => $request->membro_id,
                    'valor' => $request->valor,
                    'data' => $request->data,
                    'user_id' => Auth::id(),
                    'empresa_id' => Auth::user()->empresa_id,
                    'caixa_id' => $caixa->id,
                    'ano_referencia' => \Carbon\Carbon::parse($request->data)->year,
                    'mes_referencia' => \Carbon\Carbon::parse($request->data)->month,
                ]);
            }

            return redirect()->route('caixa.index')->with('success', 'Movimentação registrada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Caixa $caixa)
    {
        try {
            return view('caixa.show', compact('caixa'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Caixa $caixa)
    {
        try {
            // Define regras base
            $rules = [
                'descricao' => 'required|string|max:255',
                'valor' => 'required|numeric|min:0.01',
                'tipo' => 'required|in:entrada,saida',
                'data' => 'required|date',
                'categoria' => 'required|string|max:100',
                'observacao' => 'nullable|string|max:1000',
            ];

            // Validação condicional para membro_id quando for entrada e categoria Dízimo
            if ($request->tipo === 'entrada' && $request->categoria === 'Dízimo') {
                $rules['membro_id'] = 'required|exists:membros,id';
            }

            $request->validate($rules);

            $caixa->descricao = $request->descricao;
            $caixa->valor = $request->valor;
            $caixa->tipo = $request->tipo;
            $caixa->data = $request->data;
            $caixa->categoria = $request->categoria;
            $caixa->observacao = $request->observacao;
            $caixa->membro_id = ($request->tipo === 'entrada' && $request->categoria === 'Dízimo') ? $request->membro_id : null;
            $caixa->save();

            // Atualiza o dízimo se for entrada e categoria Dízimo
            if ($caixa->tipo === 'entrada' && $caixa->categoria === 'Dízimo') {
                $dizimo = Dizimo::where('caixa_id', $caixa->id)->first();
                if ($dizimo) {
                    $dizimo->update([
                        'membro_id' => $request->membro_id,
                        'valor' => $request->valor,
                        'data' => $request->data,
                        'ano_referencia' => \Carbon\Carbon::parse($request->data)->year,
                        'mes_referencia' => \Carbon\Carbon::parse($request->data)->month,
                    ]);
                }
            }

            return redirect()->route('caixa.index')->with('success', 'Movimentação atualizada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caixa $caixa)
    {
        try {
            $caixa->delete();
            return redirect()->route('caixa.index')->with('success', 'Movimentação excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
