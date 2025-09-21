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
        Schema::create('ferias_periodos', function (Blueprint $table) {
             $table->id();
            $table->foreignId('ferias_id')->constrained()->onDelete('cascade');
            $table->foreignId('periodo_origem_id')->nullable()->constrained('ferias_periodos')->onDelete('set null');
            $table->tinyInteger('ordem')->nullable();
            $table->enum('tipo', ['Férias', 'Abono']);
            $table->integer('dias');
            $table->date('inicio');
            $table->date('fim');
            $table->enum('situacao', ['Planejado', 'Remarcado', 'Interrompido'])->default('Planejado');
            $table->boolean('ativo')->default(true); // indica se o período está vigente ou foi substituído
            $table->text('justificativa')->nullable();
            $table->timestamps();

            /*
            $table->foreignId('ferias_id')->constrained()->onDelete('cascade');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->integer('dias');
            $table->enum('tipo', ['ferias', 'abono'])->default('ferias');
            $table->enum('status', ['ativo', 'interrompido', 'remarcado'])->default('ativo');
            $table->text('motivo_interrupcao')->nullable();
             */

               // Adicionar índices para melhor performance
            $table->index(['ferias_id', 'situacao']);
            $table->index('inicio');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ferias_periodos');
    }
};