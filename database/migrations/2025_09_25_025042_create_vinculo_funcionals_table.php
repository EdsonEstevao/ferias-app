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
        Schema::create('vinculo_funcionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servidor_id')->constrained('servidores')->onDelete('cascade');
            $table->string('secretaria')->nullable();
            $table->string('lotacao')->nullable();
            $table->string('departamento')->nullable();
            $table->string('processo_implantacao')->nullable();
            $table->string('processo_disposicao')->nullable();
            $table->string('numero_memorando')->nullable();
            $table->string('cargo');
            $table->enum('tipo_movimentacao', ['Nomeação', 'Exoneração', 'Tornado sem efeito']);
            $table->enum('status', ['Ativo', 'Inativo'])->default('Ativo');
            $table->date('data_movimentacao'); // Data da nomeação ou exoneração
            $table->string('ato_normativo')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();

            // Adicionar índice para melhor performance
            $table->index(['servidor_id', 'data_movimentacao']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinculo_funcionals');
    }
};