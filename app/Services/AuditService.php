<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function log(
        string $action,
        string $description,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $user_id = null,
    ): void {

        $userId = $user_id ?? (Auth::check() ? Auth::id() : null);


        // Não logar se não tiver usuário autenticado
        if (!$userId){
            return;
        }

        try {
             // Garante que old_values e new_values sejam arrays válidos
            $oldValues = is_array($oldValues) ? $oldValues : [];
            $newValues = is_array($newValues) ? $newValues : [];


            AuditLog::create([
                'action' => $action,
                'description' => $description,
                'old_values' => $oldValues ? json_encode($oldValues) : null,
                'new_values' => $newValues ? json_encode($newValues) : null,
                'model_type' => $model ? get_class($model) : null,
                'model_id' => $model ? $model->id : null,
                'user_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => request()->fullUrl(),
            ]);
        } catch (\Exception $e) {
            // Log do erro, mas não quebre a aplicação
            Log::error('Erro ao registrar log de auditoria: ' . $e->getMessage());

        }

    }



    /**
     * Registra login do usuário
     */
    public static function login(): void
    {
        self::log('login', 'Usuário fez login no sistema');
    }

    /**
     * Registra logout do usuário
     */

    public static function logout(): void
    {
        self::log('logout', 'Usuário fez logout do sistema');
    }
    /**
     * Registrar criação de registro (otimizado para observer)
     *
     */
    public static function created(Model $model, ?int $userId = null): void
    {
        $description = "Criou um novo registro de ". class_basename($model);

        Self::log(
            'created',
            $description,
            $model, null,
            $model->getAttributes(),
            $userId
        );
    }

    /**
     * Registra criação de registro
     */

    public static function create(Model $model): void
    {
        $description = 'Usuario criou um novo registro de '. class_basename($model);

        self::log('created',
        $description,
        $model,
        null,
        $model->getAttributes(),
        );

    }

     /**
     * Registra atualização de registro (otimizado para Observer)
     */
    public static function updated(Model $model, ?int $userId = null): void
    {
        // Ignora se não houve mudanças relevantes
        if (empty($model->getChanges()) || $model->wasRecentlyCreated) {
            return;
        }

        $description = "Atualizou registro de " . class_basename($model);

        self::log(
            'updated',
            $description,
            $model,
            $model->getOriginal(),
            $model->getChanges(),
            $userId
        );
    }

    /**
     * Registra atualização de registro
     */

    public static function update(Model $model): void
    {
        $description = 'Usuario atualizou um registro de '. class_basename($model);

        self::log('updated',
        $description,
        $model,
        $model->getOriginal(),
        $model->getAttributes(),
        );

    }

    /**
     * Registra exclusão de registro (otimizado para Observer)
     */
    public static function deleted(Model $model, ?int $userId = null): void
    {
        $description = "Removeu registro de " . class_basename($model);

        self::log(
            'deleted',
            $description,
            $model,
            $model->getOriginal(),
            null,
            $userId
        );
    }

    /**
     * Registra exclusão de registro
     */

    public static function delete(Model $model): void
    {
        $description = 'Removeu registro de '. class_basename($model);

        self::log('deleted',
        $description,
        $model,
        $model->getOriginal(),
        null,
        );
    }

    /**
     * Registra acesso a uma página/rota
     */
    public static function access(string $routeName, string $description = "") : void
    {
        $description = $description ?: 'Acessou: ' . $routeName;


        self::log('access', $description);
    }

    /**
     * Registra tentativa de ação não autorizada
     */

    public static function unauthorizedAttempt( string $action, string $details = ''): void
    {
        $description = 'Tentativa não autorizada: ' . $action;

        if($details)
        {
            $description .= ' - ' . $details;
        }


        self::log('unauthorized', $description);
    }

    /**
     * Registra erro ou exceção
     */
    public static function error(string $error, string $details = ''): void
    {
        $description = 'Erro: ' . $error;

        if($details)
        {
            $description .= ' - ' . $details;
        }

        self::log('error', $description);
    }


}