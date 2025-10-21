<?php

namespace App\Observers;

use App\Models\FeriasPeriodos;
use App\Services\AuditService;

class FeriasPeriodosObserver
{
    /**
     * Handle the FeriasPeriodos "created" event.
     */
    public function created(FeriasPeriodos $feriasPeriodos): void
    {
        //
        AuditService::created($feriasPeriodos);
    }

    /**
     * Handle the FeriasPeriodos "updated" event.
     */
    public function updated(FeriasPeriodos $feriasPeriodos): void
    {
        //
        if($feriasPeriodos->isDirty() && !$feriasPeriodos->wasRecentlyCreated) {
            AuditService::updated($feriasPeriodos);
        }
    }

    /**
     * Handle the FeriasPeriodos "deleted" event.
     */
    public function deleted(FeriasPeriodos $feriasPeriodos): void
    {
        //
        AuditService::delete($feriasPeriodos);

    }

    /**
     * Handle the FeriasPeriodos "restored" event.
     */
    public function restored(FeriasPeriodos $feriasPeriodos): void
    {
        //
        AuditService::log('restored', "Restaurou ferias_periodos {$feriasPeriodos->id}", $feriasPeriodos);
    }

    /**
     * Handle the FeriasPeriodos "force deleted" event.
     */
    public function forceDeleted(FeriasPeriodos $feriasPeriodos): void
    {
        //
        AuditService::log('force_deleted',
        "Excluiu permanentemente ferias_periodos {$feriasPeriodos->id}",
        $feriasPeriodos);

    }
}