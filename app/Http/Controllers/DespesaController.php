<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DespesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $despesas = Despesa::with('user')->orderBy('data_vencimento', 'desc')->get();
        
        // Estatísticas
        $mesAtual = date('m');
        $anoAtual = date('Y');
        
        $totalDespesasMesAtual = Despesa::whereMonth('data', $mesAtual)
                                    ->whereYear('data', $anoAtual)
                                    ->sum('valor');
        
        $totalDespesasPendentes = Despesa::where('status', 'pendente')->sum('valor');
        $totalDespesasPagas = Despesa::where('status', 'pago')->sum('valor');
        
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
            'status' => 'required|in:pendente,pago,vencido',
            'categoria' => 'required|string|max:100',
        ]);

        DB::beginTransaction();
        try {
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
            $despesa->save();
            
            // Se a despesa estiver paga, criar o registro no caixa
            if ($request->status == 'pago') {
                $caixa = new \App\Models\Caixa();
                $caixa->descricao = $request->descricao;
                $caixa->valor = $request->valor;
                $caixa->tipo = 'saida';
                $caixa->data = $request->data;
                $caixa->categoria = $request->categoria;
                $caixa->observacao = "Pagamento de despesa: " . $request->descricao;
                $caixa->user_id = Auth::id();
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
        $request->validate([
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
            'status' => 'required|in:pendente,pago,vencido',
            'categoria' => 'required|string|max:100',
        ]);

        DB::beginTransaction();
        try {
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
            if ($statusAnterior != 'pago' && $request->status == 'pago') {
                $caixa = new \App\Models\Caixa();
                $caixa->descricao = $request->descricao;
                $caixa->valor = $request->valor;
                $caixa->tipo = 'saida';
                $caixa->data = $request->data;
                $caixa->categoria = $request->categoria;
                $caixa->observacao = "Pagamento de despesa: " . $request->descricao;
                $caixa->user_id = Auth::id();
                $caixa->save();
                
                // Vincular a despesa ao registro de caixa
                $despesa->caixa_id = $caixa->id;
                $despesa->save();
            }
            // Se a despesa já estava paga e continua paga, atualizar o registro no caixa
            else if ($statusAnterior == 'pago' && $request->status == 'pago' && $despesa->caixa_id) {
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
            else if ($statusAnterior == 'pago' && $request->status != 'pago' && $despesa->caixa_id) {
                $caixa = \App\Models\Caixa::find($despesa->caixa_id);
                if ($caixa) {
                    $caixa->delete();
                    $despesa->caixa_id = null;
                    $despesa->save();
                }
            }
            
            DB::commit();
            return redirect()->route('despesas.index')->with('success', 'Despesa atualizada com sucesso!');
        } catch (\Exception $e) {
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
