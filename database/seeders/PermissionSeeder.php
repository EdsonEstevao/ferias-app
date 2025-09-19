<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Permission::create(['name' => 'criar ferias']);
        Permission::create(['name' => 'editar ferias']);
        Permission::create(['name' => 'interromper ferias']);
        Permission::create(['name' => 'remarcar ferias']);
        Permission::create(['name' => 'visualizar ferias']);
        Permission::create(['name' => 'gerar relatorios']);


         // Permissões para Ferias
        // Permission::create(['name' => 'ferias.create']);
        // Permission::create(['name' => 'ferias.view']);
        // Permission::create(['name' => 'ferias.edit']);
        // Permission::create(['name' => 'ferias.delete']);
        // Permission::create(['name' => 'ferias.approve']);


    }
}
