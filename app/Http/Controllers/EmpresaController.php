<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Plano;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmpresaController extends Controller
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
        $empresas = Empresa::with('plano')->orderBy('nome')->get();
        $planos = Plano::all();
        $usuarios = User::all();
        return view('empresas.index', compact('empresas', 'planos', 'usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $planos = Plano::all();
        return view('empresas._form', compact('planos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {



        try {
            $request->validate([
                'nome' => 'required|string|max:255',
                'cnpj' => 'nullable|string|max:20',
                'plano_id' => 'required|exists:planos,id',
                'email' => 'nullable|email|max:255',
                'telefone' => 'nullable|string|max:20',
                'responsavel' => 'required|string|max:255',
                'data_inicio_plano' => 'required|date',
                'admin_name' => 'required|string|max:255',
                'admin_email' => 'required|email|unique:users,email',
                'admin_password' => 'required|string|min:8',
            ], [
                'nome.required' => 'O nome é obrigatorio !',
                'nome.max' => 'O tamanho do nome ultrapassa o máximo !',
                'cnpj.max' => 'O tamanho do CNPJ ultrapassa o máximo !',
                'plano_id.required' => 'O plano é obrigatorio !',
                'plano_id.exists' => 'O plano não existe !',
                'email.max' => 'O tamanho do e-mail ultrapassa o máximo !',
                'telefone.max' => 'O tamanho do telefone ultrapassa o máximo !',
                'responsavel.max' => 'O tamanho do responsavel ultrapassa o máximo !',
                'data_inicio_plano.required' => 'A data de inicio do plano é obrigatorio !',
                'admin_name.required' => 'O nome do administrador é obrigatorio !',
                'admin_name.max' => 'O tamanho do nome do administrador ultrapassa o máximo !',
                'admin_email.required' => 'O e-mail do administrador é obrigatorio !',
                'admin_email.unique' => 'O e-mail do administrador já existe !',
                'admin_email.max' => 'O tamanho do e-mail do administrador ultrapassa o máximo !',
                'admin_password.required' => 'A senha do administrador é obrigatorio !',
                'admin_password.min' => 'A senha do administrador deve ter pelo menos 8 caracteres !',
            ]);


            DB::beginTransaction();
            // Criar a empresa
            $empresa = new Empresa();
            $empresa->nome = $request->nome;
            $empresa->cnpj = $request->cnpj;
            $empresa->plano_id = $request->plano_id;
            $empresa->data_inicio_plano = $request->data_inicio_plano;

            // Calcular data de fim do plano com base no período
            $plano = Plano::find($request->plano_id);
            if ($plano) {
                $dataInicio = new \DateTime($request->data_inicio_plano);
                switch ($plano->periodo) {
                    case 'mensal':
                        $dataFim = $dataInicio->modify('+1 month');
                        break;
                    case 'trimestral':
                        $dataFim = $dataInicio->modify('+3 months');
                        break;
                    case 'semestral':
                        $dataFim = $dataInicio->modify('+6 months');
                        break;
                    case 'anual':
                        $dataFim = $dataInicio->modify('+1 year');
                        break;
                    default:
                        $dataFim = $dataInicio->modify('+1 month');
                }
                $empresa->data_fim_plano = $dataFim;
            }

            $empresa->ativo = true;
            $empresa->email = $request->email;
            $empresa->telefone = $request->telefone;
            $empresa->responsavel = $request->responsavel;
            $empresa->observacoes = $request->observacoes;
            $empresa->save();

            // Criar o usuário administrador da empresa
            $user = new User();
            $user->name = $request->admin_name;
            $user->email = $request->admin_email;
            $user->password = Hash::make($request->admin_password);
            $user->empresa_id = $empresa->id;
            $user->save();

            // Atribuir papel de admin ao usuário
            $user->assignRole('admin');

            DB::commit();
            return redirect()->route('empresas.index')->with('success', 'Empresa criada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->route('empresas.index')->with('error', 'Erro ao criar empresa: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
        $empresa->load('plano');
        $totalMembros = $empresa->membros()->count();
        $limitePlano = $empresa->plano->limite_membros;
        $percentualUtilizado = $limitePlano > 0 ? ($totalMembros / $limitePlano) * 100 : 0;

        $usuarios = User::where('empresa_id', $empresa->id)->get();

        return view('empresas.show', compact('empresa', 'totalMembros', 'limitePlano', 'percentualUtilizado', 'usuarios'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        $planos = Plano::all();
        return view('empresas._form', compact('empresa', 'planos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cnpj' => 'nullable|string|max:20',
            'plano_id' => 'required|exists:planos,id',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'responsavel' => 'required|string|max:255',
            'data_inicio_plano' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $planoAnterior = $empresa->plano_id;

            $empresa->nome = $request->nome;
            $empresa->cnpj = $request->cnpj;
            $empresa->plano_id = $request->plano_id;
            $empresa->data_inicio_plano = $request->data_inicio_plano;

            // Se o plano mudou, recalcular a data de fim
            if ($planoAnterior != $request->plano_id || $request->has('renovar_plano')) {
                $plano = Plano::find($request->plano_id);
                if ($plano) {
                    $dataInicio = new \DateTime($request->data_inicio_plano);
                    switch ($plano->periodo) {
                        case 'mensal':
                            $dataFim = $dataInicio->modify('+1 month');
                            break;
                        case 'trimestral':
                            $dataFim = $dataInicio->modify('+3 months');
                            break;
                        case 'semestral':
                            $dataFim = $dataInicio->modify('+6 months');
                            break;
                        case 'anual':
                            $dataFim = $dataInicio->modify('+1 year');
                            break;
                        default:
                            $dataFim = $dataInicio->modify('+1 month');
                    }
                    $empresa->data_fim_plano = $dataFim;
                }
            }

            $empresa->ativo = $request->has('ativo');
            $empresa->email = $request->email;
            $empresa->telefone = $request->telefone;
            $empresa->responsavel = $request->responsavel;
            $empresa->observacoes = $request->observacoes;
            $empresa->save();

            DB::commit();
            return redirect()->route('empresas.index')->with('success', 'Empresa atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('empresas.edit', $empresa->id)->with('error', 'Erro ao atualizar empresa: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        // // Verificar se existem membros associados a esta empresa
        // if ($empresa->membros()->count() > 0) {
        //     return redirect()->route('empresas.index')->with('error', 'Esta empresa não pode ser excluída porque possui membros cadastrados.');
        // }

        // // Verificar se existem usuários associados a esta empresa
        // if (User::where('empresa_id', $empresa->id)->count() > 0) {
        //     return redirect()->route('empresas.index')->with('error', 'Esta empresa não pode ser excluída porque possui usuários associados.');
        // }

        $empresa->delete();
        return redirect()->route('empresas.index')->with('success', 'Empresa excluída com sucesso!');
    }

    /**
     * Adicionar um novo usuário à empresa
     */
    public function addUser(Request $request, Empresa $empresa)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,user',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->empresa_id = $empresa->id;
        $user->save();

        $user->assignRole($request->role);

        return redirect()->route('empresas.show', $empresa->id)->with('success', 'Usuário adicionado com sucesso!');
    }

    /**
     * Renovar o plano da empresa
     */
    public function renovarPlano(Request $request, Empresa $empresa)
    {
        $request->validate([
            'data_inicio_plano' => 'required|date',
        ]);

        $plano = $empresa->plano;
        if ($plano) {
            $dataInicio = new \DateTime($request->data_inicio_plano);
            switch ($plano->periodo) {
                case 'mensal':
                    $dataFim = $dataInicio->modify('+1 month');
                    break;
                case 'trimestral':
                    $dataFim = $dataInicio->modify('+3 months');
                    break;
                case 'semestral':
                    $dataFim = $dataInicio->modify('+6 months');
                    break;
                case 'anual':
                    $dataFim = $dataInicio->modify('+1 year');
                    break;
                default:
                    $dataFim = $dataInicio->modify('+1 month');
            }

            $empresa->data_inicio_plano = $request->data_inicio_plano;
            $empresa->data_fim_plano = $dataFim;
            $empresa->ativo = true;
            $empresa->save();

            return redirect()->route('empresas.show', $empresa->id)->with('success', 'Plano renovado com sucesso!');
        }

        return redirect()->route('empresas.show', $empresa->id)->with('error', 'Não foi possível renovar o plano.');
    }
}
