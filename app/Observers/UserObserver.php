<?php

namespace App\Observers;

use App\Models\User;
use App\Services\AuditService;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
        AuditService::create($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Só registra se houver mudança reais
        if ($user->isDirty() && !$user->wasRecentlyCreated) {
            AuditService::update($user);
        }

    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
        AuditService::delete($user);
    }

    /**
     * Handle the User "restored" event.
     */
    // public function restored(User $user): void
    // {
    //     //
    //     AuditService::log('restored', "Restaurou usuário {$user->name}", $user);
    // }
    public function restored(User $user): void
    {
        AuditService::log(
            'restored',
            "Restaurou usuário: {$user->name}",
            $user
        );
    }

    /**
     * Handle the User "force deleted" event.
     */
    // public function forceDeleted(User $user): void
    // {
    //     //
    //     AuditService::log('deleted', "Excluiu permanentemente usuário: {$user->name}", $user);
    // }

    public function forceDeleted(User $user): void
    {
        AuditService::log(
            'force_deleted',
            "Excluiu permanentemente usuário: {$user->name}",
            $user
        );
    }


}