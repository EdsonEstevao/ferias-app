<?php

namespace App\Providers;

use App\Models\Ferias;
use App\Models\FeriasPeriodos;
use App\Models\User;
use App\Observers\FeriasObserver;
use App\Observers\FeriasPeriodosObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $listen = [

    ];
    public function register(): void
    {
        //
         $this->listen = [
            Registered::class => [
                SendEmailVerificationNotification::class,
            ],
        ];
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Registra os observadores

        // User::observe(UserObserver::class);
        // Ferias::observe(FeriasObserver::class);

        $this->registerObservers();
    }

    /**
     * Método alternativo para registrar vários observadores
     */

    protected function registerObservers()
    {
        $observers = [
            // User::class => UserObserver::class,
            Ferias::class => FeriasObserver::class,
            FeriasPeriodos::class => FeriasPeriodosObserver::class
        ];

        foreach($observers as $model => $observer) {
            $model::observe($observer);
        }
    }
}
