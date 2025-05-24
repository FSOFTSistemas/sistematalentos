<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Despesa extends Model
{
    use HasFactory;
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'descricao',
        'valor',
        'data',
        'data_vencimento',
        'status',
        'categoria',
        'fornecedor',
        'numero_documento',
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
        'data_vencimento' => 'date',
        'valor' => 'decimal:2',
        'status' => 'string'
    ];
    
    /**
     * Obtém o registro de caixa relacionado.
     */
    public function caixa(): BelongsTo
    {
        return $this->belongsTo(Caixa::class);
    }
    
    /**
     * Obtém o usuário que registrou a despesa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
