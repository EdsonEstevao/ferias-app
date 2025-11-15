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
        Schema::create('ferias_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ferias_periodo_id')->constrained('ferias_periodos')->onDelete('cascade');
            $table->enum('acao', ['Interrupção', 'Remarcação']);
            $table->text('descricao')->nullable();
            $table->timestamp('data_acao');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // quem fez a ação
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ferias_eventos');
    }
};