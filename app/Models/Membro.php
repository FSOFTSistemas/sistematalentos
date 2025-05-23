<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membro extends Model
{
    use HasFactory;
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf',
        'data_nascimento',
        'endereco',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'status',
        'data_batismo',
        'data_admissao',
        'observacoes',
        'empresa_id',
    ];
    
    /**
     * Os atributos que devem ser convertidos.
     *
     * @var array
     */
    protected $casts = [
        'data_nascimento' => 'date',
        'data_batismo' => 'date',
        'data_admissao' => 'date',
        'status' => 'string'
    ];
    
    /**
     * Obtém os dízimos do membro.
     */
    public function dizimos(): HasMany
    {
        return $this->hasMany(Dizimo::class);
    }
}
