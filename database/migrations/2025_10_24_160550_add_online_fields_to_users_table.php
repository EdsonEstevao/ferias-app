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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->timestamp('last_activity_at')->nullable()->after('remember_token');
            $table->boolean('is_online')->default(false)->after('last_activity_at');
            $table->string('last_ip')->nullable()->after('is_online');
            $table->string('user_agent')->nullable()->after('last_ip');

            // Índices para melhor performance
            $table->index(['is_online', 'last_activity_at']);
            $table->index('last_activity_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropIndex(['is_online', 'last_activity_at']);
            $table->dropIndex(['last_activity_at']);

            $table->dropColumn([
                'last_activity_at',
                'is_online',
                'last_ip',
                'user_agent'
            ]);
        });
    }
};
