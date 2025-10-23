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
            $table->enum('situacao', [
                            'Planejado',
                            'Remarcado',
                            'Interrompido',
                            'Usufruido',
                            'Cancelado'
                        ])->default('Planejado')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ferias_periodos', function (Blueprint $table) {
            //

            $table->enum('situacao', [
                'Planejado',
                'Remarcado',
                'Interrompido'
            ])->default('Planejado')->change();

        });
    }
};
