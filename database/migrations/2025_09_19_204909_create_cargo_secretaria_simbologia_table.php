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
        Schema::create('cargo_secretaria_simbologia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained()->onDelete('cascade');
            $table->foreignId('simbologia_id')->constrained()->onDelete('cascade');
            $table->foreignId('secretaria_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Adicionar índice para melhor performance
            // $table->index(['cargo_id', 'simbologia_id', 'secretaria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_secretaria_simbologia');
    }
};