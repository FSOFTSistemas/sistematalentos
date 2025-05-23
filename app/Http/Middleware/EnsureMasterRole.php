<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\EmpresaController;

class EnsureMasterRole
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
        
        // Verificar se o usuário tem o papel de master
        if (!Auth::user()->hasRole('master')) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado. Você não tem permissão para acessar esta área.');
        }
        
        return $next($request);
    }
}
