<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServidorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // // Limpar tabela
        // DB::table('employees')->delete();

        $employees = [
            [
                'nome' => 'João Silva',
                'cpf' => '123.456.789-00',
                'email' => 'joao.silva@empresa.com',
                'matricula' => '001',
                'telefone' => '(11) 99999-9999',
                'secretaria' => 'Secretaria de Administração',
                'lotacao' => 'Divisão de Pessoal',
                'departamento' => 'RH',
                'processo_implantacao' => '001/2023',
                'processo_disposicao' => '002/2023',
                'numero_memorando' => '123/2023',
            ],
            [
                'nome' => 'Maria Santos',
                'cpf' => '987.654.321-00',
                'email' => 'maria.santos@empresa.com',
                'matricula' => '002',
                'telefone' => '(11) 98888-8888',
                'secretaria' => 'Secretaria de Finanças',
                'lotacao' => 'Divisão de Contabilidade',
                'departamento' => 'Financeiro',
                'processo_implantacao' => '003/2023',
                'processo_disposicao' => '004/2023',
                'numero_memorando' => '124/2023',
            ],
            // Adicione mais servidores conforme necessário
        ];

        foreach ($employees as $employee) {
            // Employee::create($employee);
        }

        $this->command->info('Servidores criados com sucesso!');
    }
}
