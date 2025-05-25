<?php

namespace Database\Factories;

use App\Models\Dizimo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dizimo>
 */
class DizimoFactory extends Factory
{
    protected $model = Dizimo::class;

    public function definition(): array
    {
        $data = $this->faker->dateTimeBetween('-1 month', 'now');

        return [
            'valor' => $this->faker->randomFloat(2, 5, 200),
            'data' => $data,
            'mes_referencia' => Carbon::parse($data)->format('F'),
            'ano_referencia' => Carbon::parse($data)->year,
            'observacao' => $this->faker->optional()->sentence(),
        ];
    }
}
