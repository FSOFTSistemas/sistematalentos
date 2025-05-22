<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEmpresaLimite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Usuários master podem acessar tudo
        if (Auth::user()->hasRole('master')) {
            return $next($request);
        }
        
        // Verificar se o usuário está associado a uma empresa
        $empresa = Auth::user()->empresa;
        if (!$empresa) {
            return redirect()->route('dashboard')->with('error', 'Sua conta não está associada a nenhuma empresa.');
        }
        
        // Verificar se a empresa tem um plano ativo
        if (!$empresa->planoAtivo()) {
            return redirect()->route('dashboard')->with('error', 'O plano da sua empresa não está ativo. Entre em contato com o administrador.');
        }
        
        return $next($request);
    }
}
