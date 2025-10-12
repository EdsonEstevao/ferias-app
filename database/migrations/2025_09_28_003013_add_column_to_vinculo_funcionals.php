<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vinculo_funcionals', function (Blueprint $table) {
            //
             $table->date('data_saida')->nullable()->after('data_movimentacao'); // Data de exoneração, se aplicável
            $table->string('tipo_servidor')->nullable()->after('data_saida');
            $table->string('escolaridade')->nullable()->after('tipo_servidor');
            $table->string('curso_superior')->nullable()->after('escolaridade');
            $table->boolean('is_diretor')->default(false)->after('curso_superior');
            // Dados Pessoais


            $table->string('rg')->nullable()->after('is_diretor');
            $table->string('orgao_expedidor')->nullable()->after('rg');
            $table->date('data_expedicao')->nullable()->after('orgao_expedidor');
            $table->date('data_nascimento')->nullable()->after('data_expedicao');
            $table->string('naturalidade')->nullable()->after('data_nascimento');
            $table->string('nacionalidade')->nullable()->after('naturalidade');
            $table->string('nome_mae')->nullable()->after('nacionalidade');
            $table->string('nome_pai')->nullable()->after('nome_mae');
            $table->string('estado_civil')->nullable()->after('nome_pai');
            $table->string('sexo')->nullable()->after('estado_civil');
            // Endereço
            $table->string('cep')->nullable()->after('sexo');
            $table->string('endereco')->nullable()->after('cep');
            $table->string('numero')->nullable()->after('endereco');
            $table->string('complemento')->nullable()->after('numero');
            $table->string('bairro')->nullable()->after('complemento');
            $table->string('cidade')->nullable()->after('bairro');
            $table->string('estado')->nullable()->after('cidade');
            $table->string('local_trabalho')->nullable()->after('estado');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vinculo_funcionals', function (Blueprint $table) {
            //
            $table->dropColumn([
                'data_saida',
                'tipo_servidor',
                'escolaridade',
                'curso_superior',
                'is_diretor',
                'rg',
                'orgao_expedidor',
                'data_expedicao',
                'data_nascimento',
                'naturalidade',
                'nacionalidade',
                'nome_mae',
                'nome_pai',
                'estado_civil',
                'sexo',
                'cep',
                'endereco',
                'numero',
                'complemento',
                'bairro',
                'cidade',
                'estado',
                'local_trabalho'
            ]);
        });
    }
};
