<?php

namespace Database\Factories;

use App\Models\Despesa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Despesa>
 */
class DespesaFactory extends Factory
{
    protected $model = Despesa::class;

    public function definition(): array
    {
        return [
            'descricao' => $this->faker->sentence(3),
            'valor' => $this->faker->randomFloat(2, 50, 1000),
            'data' => $this->faker->date(),
            'data_vencimento' => $this->faker->optional()->date(),
            'status' => $this->faker->randomElement(['pendente', 'paga', 'vencido']),
            'categoria' => $this->faker->word(),
            'fornecedor' => $this->faker->company(),
            'numero_documento' => $this->faker->bothify('###-###-####'),
            'observacao' => $this->faker->optional()->sentence(),
        ];
    }
}
