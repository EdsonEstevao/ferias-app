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
        Schema::create('ferias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servidor_id')->constrained('servidores')->onDelete('cascade');
            $table->year('ano_exercicio');
            $table->enum('situacao', ['Planejado', 'Remarcado', 'Interrompido'])->default('Planejado');
            $table->string('tipo', 15); //10_10_10, completo, 10_10_10_abono, 20_10_abono, 10_10_10_abono, 10_10_10_abono


            $table->timestamps();

             // Adicionar índice para melhor performance
            $table->index(['servidor_id', 'ano_exercicio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ferias');
    }
};