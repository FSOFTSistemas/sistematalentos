<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetar cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar permissões para cada módulo
        // Caixa
        Permission::firstOrCreate(['name' => 'caixa.visualizar']);
        Permission::firstOrCreate(['name' => 'caixa.criar']);
        Permission::firstOrCreate(['name' => 'caixa.editar']);
        Permission::firstOrCreate(['name' => 'caixa.excluir']);

        // Membros
        Permission::firstOrCreate(['name' => 'membros.visualizar']);
        Permission::firstOrCreate(['name' => 'membros.criar']);
        Permission::firstOrCreate(['name' => 'membros.editar']);
        Permission::firstOrCreate(['name' => 'membros.excluir']);

        // Dízimos
        Permission::firstOrCreate(['name' => 'dizimos.visualizar']);
        Permission::firstOrCreate(['name' => 'dizimos.criar']);
        Permission::firstOrCreate(['name' => 'dizimos.editar']);
        Permission::firstOrCreate(['name' => 'dizimos.excluir']);

        // Despesas
        Permission::firstOrCreate(['name' => 'despesas.visualizar']);
        Permission::firstOrCreate(['name' => 'despesas.criar']);
        Permission::firstOrCreate(['name' => 'despesas.editar']);
        Permission::firstOrCreate(['name' => 'despesas.excluir']);

        // Relatórios
        Permission::firstOrCreate(['name' => 'relatorios.visualizar']);
        
        // Usuários
        Permission::firstOrCreate(['name' => 'usuarios.visualizar']);
        Permission::firstOrCreate(['name' => 'usuarios.criar']);
        Permission::firstOrCreate(['name' => 'usuarios.editar']);
        Permission::firstOrCreate(['name' => 'usuarios.excluir']);

        // Criar papéis e atribuir permissões
        // Admin - acesso total
        $role = Role::firstOrCreate(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        // Tesoureiro - gerencia finanças
        $role = Role::firstOrCreate(['name' => 'tesoureiro']);
        $role->givePermissionTo([
            'caixa.visualizar', 'caixa.criar', 'caixa.editar',
            'dizimos.visualizar', 'dizimos.criar', 'dizimos.editar',
            'despesas.visualizar', 'despesas.criar', 'despesas.editar',
            'relatorios.visualizar'
        ]);

        // Secretário - gerencia membros
        $role = Role::firstOrCreate(['name' => 'secretario']);
        $role->givePermissionTo([
            'membros.visualizar', 'membros.criar', 'membros.editar',
            'dizimos.visualizar', 'dizimos.criar',
            'relatorios.visualizar'
        ]);

        // Usuário padrão - visualização limitada
        $role = Role::firstOrCreate(['name' => 'usuario']);
        $role->givePermissionTo([
            'caixa.visualizar',
            'membros.visualizar',
            'dizimos.visualizar',
            'despesas.visualizar'
        ]);

        // Criar um usuário admin padrão
        $user = User::firstOrCreate(
            ['email' => 'admin@ecclesia.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        
        $user->assignRole('admin');
    }
}
