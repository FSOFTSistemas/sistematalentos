<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('planos')->insert([
            [
                'nome' => 'Plano Básico',
                'valor' => 49.90,
                'limite_membros' => 50,
                'descricao' => 'Ideal para igrejas pequenas. Inclui acesso básico ao sistema.',
                'ativo' => true,
                'periodo' => 'mensal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Plano Intermediário',
                'valor' => 129.90,
                'limite_membros' => 200,
                'descricao' => 'Indicado para igrejas médias. Inclui relatórios financeiros e controle de eventos.',
                'ativo' => true,
                'periodo' => 'mensal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Plano Avançado',
                'valor' => 299.90,
                'limite_membros' => 1000,
                'descricao' => 'Para grandes igrejas. Acesso completo ao sistema, incluindo API e suporte prioritário.',
                'ativo' => true,
                'periodo' => 'mensal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Plano Anual Econômico',
                'valor' => 999.90,
                'limite_membros' => 1000,
                'descricao' => 'Versão anual com desconto. Mesmos recursos do Plano Avançado.',
                'ativo' => true,
                'periodo' => 'anual',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
