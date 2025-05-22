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
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar permissões
        Permission::firstOrcreate(['name' => 'gerenciar planos']);
        Permission::firstOrcreate(['name' => 'gerenciar empresas']);
        Permission::firstOrcreate(['name' => 'gerenciar membros']);
        Permission::firstOrcreate(['name' => 'gerenciar dizimos']);
        Permission::firstOrcreate(['name' => 'gerenciar despesas']);
        Permission::firstOrcreate(['name' => 'ver relatorios']);

        // Criar roles e atribuir permissões
        $role = Role::firstOrcreate(['name' => 'master']);
        $role->givePermissionTo(Permission::all());

        $role = Role::firstOrcreate(['name' => 'admin']);
        $role->givePermissionTo([
            'gerenciar membros',
            'gerenciar dizimos',
            'gerenciar despesas',
            'ver relatorios'
        ]);

        $role = Role::firstOrcreate(['name' => 'user']);
        $role->givePermissionTo([
            'ver relatorios'
        ]);
    }
}
