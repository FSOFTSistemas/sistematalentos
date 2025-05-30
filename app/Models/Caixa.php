<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Caixa extends Model
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
        'tipo',
        'data',
        'categoria',
        'observacao',
        'user_id',
        'empresa_id',
        'membro_id',
    ];

    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'date',
        'valor' => 'decimal:2'
    ];

    /**
     * Obtém o usuário que registrou a entrada/saída.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function membro(): BelongsTo
    {
        return $this->belongsTo(Membro::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function scopeEntrada($query)
    {
        return $query->where('tipo', 'entrada');
    }

    public function scopeSaida($query)
    {
        return $query->where('tipo', 'saida');
    }
}
