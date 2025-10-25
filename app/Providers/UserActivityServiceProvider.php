<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;

class UserActivityServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        Schedule::call(function () {
            // agendar limpeza de usuarios inativos
            User::where('is_online', true)
                ->where('last_activity_at', '<=', now()->subMinutes(10))
                ->update(['is_online' => false]);

                Cache::forget('online_users_count');

        })->everyFiveMinutes();
    }
}
