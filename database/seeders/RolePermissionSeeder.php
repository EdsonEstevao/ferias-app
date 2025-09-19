<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin = Role::create(['name' => 'admin']);
        $gestor = Role::create(['name' => 'gestor']);
        $servidor = Role::create(['name' => 'servidor']);

        $admin->givePermissionTo(Permission::all());
        $gestor->givePermissionTo(['criar ferias', 'editar ferias', 'visualizar ferias']);
        $servidor->givePermissionTo(['visualizar ferias']);


        // // Criar roles e atribuir permissões
        // $adminRole = Role::create(['name' => 'admin']);
        // $adminRole->givePermissionTo([
        //     'ferias.create',
        //     'ferias.view',
        //     'ferias.edit',
        //     'ferias.delete',
        //     'ferias.approve'
        // ]);

        // $userRole = Role::create(['name' => 'user']);
        // $userRole->givePermissionTo([
        //     'ferias.create',
        //     'ferias.view',
        //     'ferias.edit',
        //     'ferias.delete'
        // ]);


    }
}
