<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    use HasFactory;

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'cnpj',
        'plano_id',
        'data_inicio_plano',
        'data_fim_plano',
        'ativo',
        'email',
        'telefone',
        'responsavel',
        'observacoes',
    ];

    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'data_inicio_plano' => 'date',
        'data_fim_plano' => 'date',
        'ativo' => 'boolean',
    ];

    /**
     * Obtém o plano associado à empresa.
     */
    public function plano(): BelongsTo
    {
        return $this->belongsTo(Plano::class);
    }

    /**
     * Obtém os membros associados à empresa.
     */
    public function membros(): HasMany
    {
        return $this->hasMany(Membro::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Verifica se a empresa atingiu o limite de membros do plano.
     */
    public function atingiuLimiteMembros(): bool
    {
        $totalMembros = $this->membros()->count();
        $limitePlano = $this->plano->limite_membros;

        return $totalMembros >= $limitePlano;
    }

    /**
     * Retorna o número de membros restantes que podem ser cadastrados.
     */
    public function membrosRestantes(): int
    {
        $totalMembros = $this->membros()->count();
        $limitePlano = $this->plano->limite_membros;

        return max(0, $limitePlano - $totalMembros);
    }

    /**
     * Verifica se a empresa está com o plano ativo.
     */
    public function planoAtivo(): bool
    {
        if (!$this->ativo || !$this->plano->ativo) {
            return false;
        }

        if ($this->data_fim_plano && $this->data_fim_plano->isPast()) {
            return false;
        }

        return true;
    }
}
