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
            $table->boolean('usufruido')->default(false)->after('situacao');
            $table->timestamp('data_usufruto')->nullable()->after('usufruido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ferias_periodos', function (Blueprint $table) {
            //
            $table->dropColumn(['usufruido', 'data_usufruto']);
        });
    }
};