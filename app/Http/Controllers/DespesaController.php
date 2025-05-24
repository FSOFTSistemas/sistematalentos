<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DespesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresa_id = Auth::user()->empresa_id;
        $despesas = Despesa::with('user')->where('empresa_id', $empresa_id)->orderBy('data_vencimento', 'desc')->get();

        // Estatísticas
        $mesAtual = date('m');
        $anoAtual = date('Y');

        $totalDespesasMesAtual = Despesa::whereMonth('data', (int) $mesAtual)
            ->whereYear('data', (int) $anoAtual)
            ->where('empresa_id', $empresa_id)
            ->sum('valor');

        $totalDespesasPendentes = Despesa::where('status', 'pendente')->where('empresa_id', $empresa_id)->sum('valor');
        $totalDespesasPagas = Despesa::where('status', 'paga')->where('empresa_id', $empresa_id)->sum('valor');

        return view('despesas.index', compact('despesas', 'totalDespesasMesAtual', 'totalDespesasPendentes', 'totalDespesasPagas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
            'status' => 'required|in:pendente,paga,vencido',
            'categoria' => 'required|string|max:100',
        ]);

        DB::beginTransaction();
        try {
            $empresa_id = Auth::user()->empresa_id;
            // Criar o registro de despesa
            $despesa = new Despesa();
            $despesa->descricao = $request->descricao;
            $despesa->valor = $request->valor;
            $despesa->data = $request->data;
            $despesa->data_vencimento = $request->data_vencimento;
            $despesa->status = $request->status;
            $despesa->categoria = $request->categoria;
            $despesa->fornecedor = $request->fornecedor;
            $despesa->numero_documento = $request->numero_documento;
            $despesa->observacao = $request->observacao;
            $despesa->user_id = Auth::id();
            $despesa->empresa_id = $empresa_id;
            $despesa->save();

            // Se a despesa estiver paga, criar o registro no caixa
            if ($request->status == 'paga') {
                $caixa = new \App\Models\Caixa();
                $caixa->descricao = $request->descricao;
                $caixa->valor = $request->valor;
                $caixa->tipo = 'saida';
                $caixa->data = $request->data;
                $caixa->categoria = $request->categoria;
                $caixa->observacao = "Pagamento de despesa: " . $request->descricao;
                $caixa->user_id = Auth::id();
                $caixa->empresa_id = $empresa_id;
                $caixa->save();

                // Vincular a despesa ao registro de caixa
                $despesa->caixa_id = $caixa->id;
                $despesa->save();
            }

            DB::commit();
            return redirect()->route('despesas.index')->with('success', 'Despesa registrada com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('despesas.index')->with('error', 'Erro ao registrar despesa: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Despesa $despesa)
    {
        return view('despesas.show', compact('despesa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Despesa $despesa)
    {


        DB::beginTransaction();
        try {
            $request->validate([
                'descricao' => 'required|string|max:255',
                'valor' => 'required|numeric|min:0.01',
                'data' => 'required|date',
                'status' => 'required|in:pendente,paga,vencido',
                'categoria' => 'required|string|max:100',
            ], [
                'descricao.required' => 'O campo descrição é obrigatório',
                'descricao.max' => 'O campo descrição deve ter no máximo 255 caracteres',
                'valor.required' => 'O campo valor é obrigatório',
                'valor.numeric' => 'O campo valor deve ser um número',
                'valor.min' => 'O campo valor deve ser maior ou igual a 0,01',
                'data.required' => 'O campo data é obrigatório',
                'data.date' => 'O campo data deve ser uma data válida',
                'status.required' => 'O campo status é obrigatório',
                'status.in' => 'O campo status deve ser pendente, paga ou vencido',
                'categoria.required' => 'O campo categoria é obrigatório',
                'categoria.max' => 'O campo categoria deve ter no máximo 100 caracteres',
            ]);
            $statusAnterior = $despesa->status;

            // Atualizar o registro de despesa
            $despesa->descricao = $request->descricao;
            $despesa->valor = $request->valor;
            $despesa->data = $request->data;
            $despesa->data_vencimento = $request->data_vencimento;
            $despesa->status = $request->status;
            $despesa->categoria = $request->categoria;
            $despesa->fornecedor = $request->fornecedor;
            $despesa->numero_documento = $request->numero_documento;
            $despesa->observacao = $request->observacao;
            $despesa->save();

            // Se a despesa passou a ser paga, criar o registro no caixa
            if ($statusAnterior != 'paga' && $request->status == 'paga') {
                $caixa = new \App\Models\Caixa();
                $caixa->descricao = $request->descricao;
                $caixa->valor = $request->valor;
                $caixa->tipo = 'saida';
                $caixa->data = $request->data;
                $caixa->categoria = $request->categoria;
                $caixa->observacao = "Pagamento de despesa: " . $request->descricao;
                $caixa->user_id = Auth::id();
                $caixa->empresa_id = Auth::user()->empresa_id;
                $caixa->save();

                // Vincular a despesa ao registro de caixa
                $despesa->caixa_id = $caixa->id;
                $despesa->save();
            }
            // Se a despesa já estava paga e continua paga, atualizar o registro no caixa
            else if ($statusAnterior == 'paga' && $request->status == 'paga' && $despesa->caixa_id) {
                $caixa = \App\Models\Caixa::find($despesa->caixa_id);
                if ($caixa) {
                    $caixa->descricao = $request->descricao;
                    $caixa->valor = $request->valor;
                    $caixa->data = $request->data;
                    $caixa->categoria = $request->categoria;
                    $caixa->observacao = "Pagamento de despesa: " . $request->descricao;
                    $caixa->save();
                }
            }
            // Se a despesa deixou de ser paga, excluir o registro no caixa
            else if ($statusAnterior == 'paga' && $request->status != 'paga' && $despesa->caixa_id) {
                $caixa = \App\Models\Caixa::find($despesa->caixa_id);
                if ($caixa) {
                    $caixa->delete();
                    $despesa->caixa_id = null;
                    $despesa->save();
                }
            }

            DB::commit();
            return redirect()->route('despesas.index')->with('success', 'Despesa atualizada com sucesso!');
        }catch(ValidationException $v){
             DB::rollBack();
             return redirect()->route('despesas.index')->with('error', 'Erro ao atualizar despesa: ' . $v->getMessage());
        
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            return redirect()->route('despesas.index')->with('error', 'Erro ao atualizar despesa: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Despesa $despesa)
    {
        DB::beginTransaction();
        try {
            // Excluir o registro no caixa, se existir
            if ($despesa->caixa_id) {
                $caixa = \App\Models\Caixa::find($despesa->caixa_id);
                if ($caixa) {
                    $caixa->delete();
                }
            }

            // Excluir o registro de despesa
            $despesa->delete();

            DB::commit();
            return redirect()->route('despesas.index')->with('success', 'Despesa excluída com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('despesas.index')->with('error', 'Erro ao excluir despesa: ' . $e->getMessage());
        }
    }
}
