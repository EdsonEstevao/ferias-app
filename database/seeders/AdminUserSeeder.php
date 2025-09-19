<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $adminRole = Role::where('name', 'admin')->first();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@empresa.com',
            'password' => Hash::make('password'),
        ]);

        $servidorRole = Role::where('name', 'servidor')->first();

        $servidor = User::create([
            'name' => 'Servidor',
            'email' => 'servidor@empresa.com',
            'password' => Hash::make('password'),
        ]);

        $gestorRole = Role::where('name', 'gestor')->first();

        $gestor = User::create([
            'name' => 'Gestor',
            'email' => 'gestor@empresa.com',
            'password' => Hash::make('password'),
        ]);

        $gestor->assignRole($gestorRole);
        $servidor->assignRole($servidorRole);
        $admin->assignRole($adminRole);


    }
}