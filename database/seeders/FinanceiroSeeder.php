<?php

namespace Database\Seeders;

use App\Models\Caixa;
use App\Models\Despesa;
use App\Models\Dizimo;
use App\Models\Membro;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FinanceiroSeeder extends Seeder
{
    public function run(): void
    {
        // Criar 10 membros
        $membros = Membro::factory(10)->create([
            'empresa_id' => 1,
        ]);

        // Criar 3 despesas
        Despesa::factory()->count(3)->create([
            'empresa_id' => 1,
            'user_id' => User::inRandomOrder()->first()?->id,
            'caixa_id' => null,
        ]);

        // Criar 20 lançamentos no caixa com tipo variando entre entrada e saída
        for ($i = 0; $i < 20; $i++) {
            Caixa::create([
                'descricao' => 'Movimentação financeira ' . ($i + 1),
                'valor' => rand(5000, 20000) / 100,
                'tipo' => $i % 2 === 0 ? 'entrada' : 'saida',
                'data' => Carbon::now()->subDays(rand(0, 60)),
                'categoria' => Str::random(10),
                'observacao' => 'Observação aleatória',
                'user_id' => User::inRandomOrder()->first()?->id,
                'empresa_id' => 1,
            ]);
        }

        // Criar 8 dízimos vinculados a membros e lançamentos no caixa
        $caixasEntrada = Caixa::where('tipo', 'entrada')->pluck('id');

        for ($i = 0; $i < 8; $i++) {
            $membro = $membros->random();
            $data = Carbon::now()->subDays(rand(0, 30));

            Dizimo::create([
                'membro_id' => $membro->id,
                'valor' => rand(500, 1500) / 100,
                'data' => $data,
                'mes_referencia' => $data->format('F'),
                'ano_referencia' => $data->year,
                'caixa_id' => $caixasEntrada->random(),
                'user_id' => User::inRandomOrder()->first()?->id,
                'observacao' => 'Dízimo do mês de ' . $data->format('F'),
                'empresa_id' => 1,
            ]);
        }
    }
}
