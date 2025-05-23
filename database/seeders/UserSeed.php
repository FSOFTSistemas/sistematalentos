<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário Master
        $master = User::firstOrCreate(
            ['email' => 'master@ecclesia.com'],
            [
                'name' => 'Master Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$master->hasRole('master')) {
            $master->assignRole('master');
        }

        // Criar usuário Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@ecclesia.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
