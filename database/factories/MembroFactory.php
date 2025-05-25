<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Membro;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Membro>
 */
class MembroFactory extends Factory
{
    protected $model = Membro::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefone' => $this->faker->phoneNumber(),
            'cpf' => $this->faker->unique()->numerify('###.###.###-##'),
            'data_nascimento' => $this->faker->date(),
            'endereco' => $this->faker->streetAddress(),
            'bairro' => $this->faker->word(),
            'cidade' => $this->faker->city(),
            'estado' => $this->faker->stateAbbr(),
            'cep' => $this->faker->postcode(),
            'status' => $this->faker->randomElement(['ativo', 'inativo']),
            'data_batismo' => $this->faker->date(),
            'data_admissao' => $this->faker->date(),
            'observacoes' => $this->faker->sentence(),
        ];
    }
}
