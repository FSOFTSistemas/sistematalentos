<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dizimo extends Model
{
    use HasFactory;
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'membro_id',
        'valor',
        'data',
        'mes_referencia',
        'ano_referencia',
        'caixa_id',
        'user_id',
        'observacao',
        'empresa_id',
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'date',
        'valor' => 'decimal:2',
        'ano_referencia' => 'integer'
    ];
    
    /**
     * Obtém o membro que pagou o dízimo.
     */
    public function membro(): BelongsTo
    {
        return $this->belongsTo(Membro::class);
    }
    
    /**
     * Obtém o registro de caixa relacionado.
     */
    public function caixa(): BelongsTo
    {
        return $this->belongsTo(Caixa::class);
    }
    
    /**
     * Obtém o usuário que registrou o dízimo.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
