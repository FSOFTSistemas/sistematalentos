<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::paginate(10);
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255|unique:categorias,nome',
            ], [
                'nome.required' => 'O nome da categoria é obrigatório.',
                'nome.unique' => 'Essa categoria já existe.',
            ]);

            Categoria::create($validated);
            return redirect()->back()->with('success', 'Categoria cadastrada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao cadastrar categoria.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        return view('categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255|unique:categorias,nome,' . $categoria->id,
            ], [
                'nome.required' => 'O nome da categoria é obrigatório.',
                'nome.unique' => 'Esse nome de categoria já está em uso.',
            ]);

            $categoria->update($validated);
            return redirect()->route('categorias.index')->with('success', 'Categoria atualizada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar categoria.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        try {
            $categoria->delete();
            return redirect()->route('categorias.index')->with('success', 'Categoria excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('categorias.index')->with('error', 'Erro ao excluir categoria.');
        }
    }
}
