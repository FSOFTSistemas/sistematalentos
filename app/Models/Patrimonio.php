<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;

class Patrimonio extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'numero_patrimonio',
        'categoria_id',
        'data_aquisicao',
        'valor_aquisicao',
        'valor_atual',
        'localizacao',
        'responsavel',
        'estado_conservacao',
        'ativo',
        'observacoes',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
