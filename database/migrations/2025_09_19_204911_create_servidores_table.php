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
       Schema::create('servidores', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cpf')->unique();
            $table->string('email')->unique();
            $table->string('matricula')->unique();
            $table->string('telefone')->nullable();
            $table->string('secretaria');
            $table->string('lotacao');
            $table->string('departamento');
            $table->string('processo_implantacao')->nullable();
            $table->string('processo_disposicao')->nullable();
            $table->string('numero_memorando')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servidores');
    }
};
