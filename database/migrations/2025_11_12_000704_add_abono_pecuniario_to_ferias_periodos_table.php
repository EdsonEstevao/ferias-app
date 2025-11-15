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
        Schema::table('ferias_periodos', function (Blueprint $table) {
            //
            $table->boolean('convertido_abono')->default(false)->after('ativo');
            $table->timestamp('data_conversao_abono')->nullable()->after('convertido_abono');
            $table->text('justificativa_abono')->nullable()->after('data_conversao_abono');
            $table->string('url_abono')->nullable()->after('justificativa_abono');
            $table->string('title_abono')->nullable()->after('url_abono');

        });

        // Atualizar a tabela ferias_eventos para incluir as novas ações
        Schema::table('ferias_eventos', function (Blueprint $table) {
            $table->enum('acao', [
                'Interrupção',
                'Remarcação',
                'Usufruto',
                'Desmarcar Usufruto',
                'Conversão Abono Pecuniário',
                'Reversão Abono Pecuniário',
                'Conversão Parcial Abono Pecuniário',
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ferias_periodos', function (Blueprint $table) {

            //
            $table->dropColumn(['convertido_abono', 'data_conversao_abono', 'justificativa_abono', 'url_abono', 'title_abono']);

        });

        // Reverter as ações na tabela ferias_eventos
        Schema::table('ferias_eventos', function (Blueprint $table) {
            $table->enum('acao', ['Interrupção', 'Remarcação'])->change();
        });
    }
};