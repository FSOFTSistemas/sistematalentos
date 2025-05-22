<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compartilhar informações do plano com todas as views
        view()->composer('*', function ($view) {
            if (Auth::check() && !Auth::user()->hasRole('master')) {
                $empresa = Auth::user()->empresa;
                if ($empresa) {
                    $totalMembros = $empresa->membros()->count();
                    $limitePlano = $empresa->plano ? $empresa->plano->limite_membros : 0;
                    $percentualUtilizado = $limitePlano > 0 ? ($totalMembros / $limitePlano) * 100 : 0;
                    $membrosRestantes = $limitePlano - $totalMembros;
                    
                    $view->with([
                        'empresa_atual' => $empresa,
                        'plano_atual' => $empresa->plano,
                        'total_membros' => $totalMembros,
                        'limite_plano' => $limitePlano,
                        'percentual_utilizado' => $percentualUtilizado,
                        'membros_restantes' => $membrosRestantes,
                        'plano_ativo' => $empresa->planoAtivo(),
                    ]);
                }
            }
        });
        
        // Diretivas Blade personalizadas para verificação de papéis
        Blade::if('master', function () {
            return Auth::check() && Auth::user()->hasRole('master');
        });
        
        Blade::if('admin', function () {
            return Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('master'));
        });
        
        Blade::if('planoAtivo', function () {
            if (Auth::check() && Auth::user()->hasRole('master')) {
                return true;
            }
            
            if (Auth::check() && Auth::user()->empresa) {
                return Auth::user()->empresa->planoAtivo();
            }
            
            return false;
        });
        
        Blade::if('temLimite', function () {
            if (Auth::check() && Auth::user()->hasRole('master')) {
                return true;
            }
            
            if (Auth::check() && Auth::user()->empresa) {
                return !Auth::user()->empresa->atingiuLimiteMembros();
            }
            
            return false;
        });
    }
}
