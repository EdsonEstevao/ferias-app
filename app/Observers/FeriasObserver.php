<?php

namespace App\Observers;

use App\Models\Ferias;
use App\Services\AuditService;

class FeriasObserver
{
    /**
     * Handle the Ferias "created" event.
     */
    public function created(Ferias $ferias): void
    {
        //
        AuditService::create($ferias);
    }

    /**
     * Handle the Ferias "updated" event.
     */
    public function updated(Ferias $ferias): void
    {
        //
        if($ferias->isDirty() && !$ferias->wasRecentlyCreated) {
            AuditService::update($ferias);
        }
    }

    /**
     * Handle the Ferias "deleted" event.
     */
    public function deleted(Ferias $ferias): void
    {
        //
        AuditService::delete($ferias);
    }

    /**
     * Handle the Ferias "restored" event.
     */
    public function restored(Ferias $ferias): void
    {
        //
    }

    /**
     * Handle the Ferias "force deleted" event.
     */
    public function forceDeleted(Ferias $ferias): void
    {
        //
    }
}