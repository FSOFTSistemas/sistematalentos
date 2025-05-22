<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plano extends Model
{
    use HasFactory;
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'valor',
        'limite_membros',
        'descricao',
        'ativo',
        'periodo',
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'valor' => 'decimal:2',
        'limite_membros' => 'integer',
        'ativo' => 'boolean',
    ];
    
    /**
     * Obtém as empresas que usam este plano.
     */
    public function empresas(): HasMany
    {
        return $this->hasMany(Empresa::class);
    }
    
    /**
     * Verifica se o plano está ativo.
     */
    public function isAtivo(): bool
    {
        return $this->ativo;
    }
    
    /**
     * Retorna o valor formatado como moeda.
     */
    public function getValorFormatadoAttribute(): string
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }
    
    /**
     * Retorna o período formatado.
     */
    public function getPeriodoFormatadoAttribute(): string
    {
        $periodos = [
            'mensal' => 'Mensal',
            'trimestral' => 'Trimestral',
            'semestral' => 'Semestral',
            'anual' => 'Anual',
        ];
        
        return $periodos[$this->periodo] ?? $this->periodo;
    }
}
