<?php

namespace App\Http\Controllers;

use App\Models\Patrimonio;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PatrimonioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patrimonios = Patrimonio::with('categoria')->paginate(10);
        return view('patrimonios.index', compact('patrimonios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('patrimonios._form', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'descricao' => 'required|string|max:255',
                'numero_patrimonio' => 'required|string|unique:patrimonios,numero_patrimonio',
                'categoria_id' => 'required|exists:categorias,id',
                'data_aquisicao' => 'nullable|date',
                'valor_aquisicao' => 'nullable|numeric',
                'localizacao' => 'nullable|string|max:255',
                'responsavel' => 'nullable|string|max:255',
                'estado_conservacao' => 'nullable|string|max:255',
                'ativo' => 'boolean',
                'observacoes' => 'nullable|string',
            ], [
                'descricao.required' => 'A descrição é obrigatória.',
                'numero_patrimonio.required' => 'O número de patrimônio é obrigatório.',
                'numero_patrimonio.unique' => 'Esse número de patrimônio já está cadastrado.',
                'categoria_id.required' => 'A categoria é obrigatória.',
                'categoria_id.exists' => 'Categoria selecionada é inválida.',
            ]);

            Patrimonio::create($validated);
            return redirect()->route('patrimonios.index')->with('success', 'Patrimônio cadastrado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao cadastrar patrimônio.'.$e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Patrimonio $patrimonio)
    {
        return view('patrimonios.show', compact('patrimonio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patrimonio $patrimonio)
    {
        $categorias = Categoria::all();
        return view('patrimonios._form', compact('patrimonio', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patrimonio $patrimonio)
    {
        try {
            $validated = $request->validate([
                'descricao' => 'required|string|max:255',
                'numero_patrimonio' => 'required|string|unique:patrimonios,numero_patrimonio,' . $patrimonio->id,
                'categoria_id' => 'required|exists:categorias,id',
                'data_aquisicao' => 'nullable|date',
                'valor_aquisicao' => 'nullable|numeric',
                'localizacao' => 'nullable|string|max:255',
                'responsavel' => 'nullable|string|max:255',
                'estado_conservacao' => 'nullable|string|max:255',
                'ativo' => 'boolean',
                'observacoes' => 'nullable|string',
            ], [
                'descricao.required' => 'A descrição é obrigatória.',
                'numero_patrimonio.required' => 'O número de patrimônio é obrigatório.',
                'numero_patrimonio.unique' => 'Esse número de patrimônio já está cadastrado.',
                'categoria_id.required' => 'A categoria é obrigatória.',
                'categoria_id.exists' => 'Categoria selecionada é inválida.',
            ]);

            $patrimonio->update($validated);
            return redirect()->route('patrimonios.index')->with('success', 'Patrimônio atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar patrimônio.'.$e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patrimonio $patrimonio)
    {
        try {
            $patrimonio->delete();
            return redirect()->route('patrimonios.index')->with('success', 'Patrimônio excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('patrimonios.index')->with('error', 'Erro ao excluir patrimônio.');
        }
    }
}
