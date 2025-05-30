<?php

namespace Database\Factories;

use App\Models\Caixa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Caixa>
 */
class CaixaFactory extends Factory
{
     protected $model = Caixa::class;

    public function definition(): array
    {
        return [
            'descricao' => $this->faker->sentence(3),
            'valor' => $this->faker->randomFloat(2, 10, 500),
            'tipo' => $this->faker->randomElement(['entrada', 'saida']),
            'data' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'categoria' => $this->faker->word(),
            'observacao' => $this->faker->optional()->sentence(),
            'empresa_id' => 1, // Adicionado aqui
            'user_id' => 1,     // Opcional: se necessário, adicione também
        ];
    }
}
