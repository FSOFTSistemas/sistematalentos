<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plano;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VendasController extends Controller
{
    /**
     * Exibe a página de vendas
     */
    public function index()
    {
        $planos = Plano::where('ativo', true)->get();
        return view('landing.index', compact('planos'));
    }

    /**
     * Processa o checkout de um plano
     */
    public function checkout(Request $request)
    {
        // Validar dados do formulário
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:20',
            'igreja' => 'required|string|max:255',
            'plano_id' => 'required|exists:planos,id',
            'payment_id' => 'required|string',
        ]);

        // Iniciar transação para garantir integridade dos dados
        DB::beginTransaction();

        try {
            // Buscar o plano selecionado
            $plano = Plano::findOrFail($validated['plano_id']);
            
            // Criar a empresa
            $empresa = new Empresa();
            $empresa->nome = $validated['igreja'];
            $empresa->email = $validated['email'];
            $empresa->telefone = $validated['telefone'];
            $empresa->responsavel = $validated['nome'];
            $empresa->plano_id = $plano->id;
            $empresa->data_inicio_plano = Carbon::now();
            
            // Definir data de fim do plano com base no período
            switch ($plano->periodo) {
                case 'mensal':
                    $empresa->data_fim_plano = Carbon::now()->addMonth();
                    break;
                case 'trimestral':
                    $empresa->data_fim_plano = Carbon::now()->addMonths(3);
                    break;
                case 'semestral':
                    $empresa->data_fim_plano = Carbon::now()->addMonths(6);
                    break;
                case 'anual':
                    $empresa->data_fim_plano = Carbon::now()->addYear();
                    break;
                default:
                    $empresa->data_fim_plano = Carbon::now()->addMonth();
            }
            
            $empresa->ativo = true;
            $empresa->save();
            
            // Criar usuário administrador para a empresa
            $user = new User();
            $user->name = $validated['nome'];
            $user->email = $validated['email'];
            $user->password = Hash::make(Str::random(10)); // Senha temporária
            $user->empresa_id = $empresa->id;
            $user->save();
            
            // Atribuir papel de admin
            $user->assignRole('admin');
            
            // Registrar pagamento
            // Em uma implementação real, você verificaria o status do pagamento com o Mercado Pago
            $pagamento = [
                'payment_id' => $validated['payment_id'],
                'status' => 'approved',
                'empresa_id' => $empresa->id,
                'plano_id' => $plano->id,
                'valor' => $plano->valor,
                'data' => Carbon::now(),
            ];
            
            // Salvar pagamento no banco de dados
            // PaymentLog::create($pagamento);
            
            // Enviar email de boas-vindas com instruções
            // Mail::to($user->email)->send(new WelcomeEmail($user, $empresa, $plano));
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Assinatura realizada com sucesso!',
                'redirect' => route('login')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar assinatura: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Webhook para receber notificações do Mercado Pago
     */
    public function webhook(Request $request)
    {
        $topic = $request->query('topic');
        $id = $request->query('id');
        
        // Log da notificação recebida
        Log::info('Mercado Pago Webhook', [
            'topic' => $topic,
            'id' => $id,
            'data' => $request->all()
        ]);
        
        if ($topic === 'payment') {
            // Em uma implementação real, você verificaria o status do pagamento
            // e atualizaria o status da assinatura correspondente
            
            // Exemplo:
            // $payment = MercadoPago\Payment::find_by_id($id);
            // $empresaId = $payment->external_reference;
            // $empresa = Empresa::find($empresaId);
            
            // if ($payment->status === 'approved') {
            //     $empresa->ativo = true;
            //     $empresa->save();
            // }
        }
        
        return response()->json(['status' => 'ok']);
    }
}
