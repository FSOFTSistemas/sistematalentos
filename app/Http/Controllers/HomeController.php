<?php

namespace App\Http\Controllers;

use App\Models\Membro;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $mesAtual = Carbon::now()->month;
        $totalMembros = Membro::count();
        $aniversariantes = Membro::whereMonth('data_nascimento', $mesAtual)->where('empresa_id', Auth::user()->empresa_id)->orderByRaw('DAY(data_nascimento) ASC')->get();
        return view('home', compact('totalMembros', 'aniversariantes'));
    }

    
}
