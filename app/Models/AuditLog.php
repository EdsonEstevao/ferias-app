<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class AuditLog extends Model
{
    //
    use HasFactory;

    /*
     *  action // create, update, delete, login, etc
        description
        old_values
        new_values
        model_type
        model_id
        user_id
        ip_address
        user_agent
        url
     */

    protected $fillable = [
        'action',
        'description',
        'old_values',
        'new_values',
        'model_type',
        'model_id',
        'user_id',
        'ip_address',
        'user_agent',
        'url'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    /**
     * Usuário que realizou a ação
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Model relacionado (polimórfico)
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Retorna as alterações formatadas
     */
    public function getChangesAttribute(): array
    {
        // if (!$this->old_values || !$this->new_values) {
        //     return [];
        // }

        // $changes = [];
        // foreach ($this->new_values as $key => $newValue) {
        //     $oldValue = $this->old_values[$key] ?? null;
        //     if ($oldValue != $newValue) {
        //         $changes[$key] = [
        //             'old' => $oldValue,
        //             'new' => $newValue
        //         ];
        //     }
        // }

        // return $changes;

        // Garante que old_values e new_values sejam arrays
        $oldValues = $this->old_values;
        $newValues = $this->new_values;

        // Se for string, tenta decodificar JSON
        if (is_string($oldValues)) {
            $oldValues = json_decode($oldValues, true) ?? [];
        }

        if (is_string($newValues)) {
            $newValues = json_decode($newValues, true) ?? [];
        }

        // Se não for array, converte para array vazio
        $oldValues = is_array($oldValues) ? $oldValues : [];
        $newValues = is_array($newValues) ? $newValues : [];

        if (empty($oldValues) && empty($newValues)) {
            return [];
        }

        $changes = [];

        // Processa new_values se existir
        if (!empty($newValues)) {
            foreach ($newValues as $key => $newValue) {
                $oldValue = $oldValues[$key] ?? null;

                // Verifica se houve mudança
                if ($oldValue != $newValue) {
                    $changes[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }
        }

        // Processa old_values para capturar campos que foram removidos
        if (!empty($oldValues)) {
            foreach ($oldValues as $key => $oldValue) {
                if (!isset($newValues[$key]) && !isset($changes[$key])) {
                    $changes[$key] = [
                        'old' => $oldValue,
                        'new' => null
                    ];
                }
            }
        }

        return $changes;
    }

     /**
     * Acessor seguro para new_values
     */
    public function getNewValuesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }

        return $value ?? [];
    }

    /**
     * Acessor seguro para old_values
     */
    public function getOldValuesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }

        return $value ?? [];
    }

    /**
     * Retorna cor baseada na ação (para badges)
     */
    // public function getActionColor(): string
    // {
    //     return match($this->action) {
    //         'created', 'login' => 'success',
    //         'updated', 'access' => 'warning',
    //         'deleted', 'error', 'unauthorized' => 'danger',
    //         'logout' => 'secondary',
    //         default => 'primary'
    //     };
    // }

    /**
     * Retorna ícone baseado na ação
     */
    public function getActionIcon(): string
    {
        return match($this->action) {
            'created' => 'fas fa-plus-circle',
            'updated' => 'fas fa-edit',
            'deleted' => 'fas fa-trash',
            'login' => 'fas fa-sign-in-alt',
            'logout' => 'fas fa-sign-out-alt',
            'access' => 'fas fa-eye',
            'error' => 'fas fa-exclamation-triangle',
            'unauthorized' => 'fas fa-ban',
            default => 'fas fa-info-circle'
        };
    }

    public function getActionIconPath(): string
    {
        return match($this->action) {
            'created' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
            'updated' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
            'deleted' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
            'login' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1',
            'logout' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
            'access' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
            default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
        };
    }

    /**
     * Retorna classes CSS para o badge
     */
    public function getActionColor(): string
    {
        return match($this->action) {
            'created', 'login' => 'green',
            'updated', 'access' => 'yellow',
            'deleted', 'error', 'unauthorized' => 'red',
            'logout' => 'gray',
            default => 'blue'
        };
    }

    /**
     * Formata a data no horário local
     */
    public function getLocalCreatedAtAttribute(): string
    {
        return $this->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i:s');
    }

    /**
     * Formata a data de forma amigável
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i:s');
    }

    /**
     * Para exibição em tabelas (mais curto)
     */
    public function getShortCreatedAtAttribute(): string
    {
        return $this->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i');
    }

    /**
     * Formata um valor para exibição, convertendo datas se necessário
     */
    // public function formatValue($value)
    // {
    //     if (is_null($value)) {
    //         return 'N/A';
    //     }

    //     // Tenta detectar e formatar datas ISO
    //     if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\//', $value)) {
    //         try {
    //             return \Carbon\Carbon::parse($value)->format('d/m/Y');
    //         } catch (\Exception $e) {
    //             // Se não conseguir parsear, retorna o valor original
    //             return $value;
    //         }
    //     }

    //     // Para outros valores, retorna como string
    //     return $value;
    // }

    /**
     * Formata um valor para exibição, convertendo datas se necessário
     */
    public function formattValue($value)
    {
        if (is_null($value)) {
            return 'N/A';
        }

        // Tenta detectar e formatar datas ISO
        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?Z?$/', $value)) {
            try {
                return \Carbon\Carbon::parse($value)->format('d/m/Y');
            } catch (\Exception $e) {
                // Se não conseguir parsear, retorna o valor original
                return $value;
            }
        }

        // Para outros valores, retorna como string
        return $value;
    }

    /**
     * Acessor para changes com datas formatadas
     */
    public function getFormattedChangesAttribute(): array
    {
        $changes = $this->changes;
        $formattedChanges = [];

        foreach ($changes as $field => $change) {
            $formattedChanges[$field] = [
                'old' => $this->formatValue($change['old'] ?? null),
                'new' => $this->formatValue($change['new'] ?? null)
            ];
        }

        return $formattedChanges;
    }

    /**
     * Acessor para changes com datas formatadas
     */
    // public function getFormattedChangesAttribute(): array
    // {
    //     $changes = $this->changes;
    //     $formattedChanges = [];

    //     foreach ($changes as $field => $change) {
    //         $formattedChanges[$field] = [
    //             'old' => $this->formatValue($change['old'] ?? null),
    //             'new' => $this->formatValue($change['new'] ?? null)
    //         ];
    //     }

    //     return $formattedChanges;
    // }

    public static function formatValueStatic($value)
    {
        return (new static())->formatValue($value);
    }

    public function formatValue($value)
    {
        if (is_null($value)) {
            return 'N/A';
        }

        // Para arrays, converte para string JSON formatada
        if (is_array($value)) {
            return json_encode($value, JSON_PRETTY_PRINT);
        }

        // Para objetos, converte para array primeiro
        if (is_object($value)) {
            $value = (array) $value;
            return json_encode($value, JSON_PRETTY_PRINT);
        }

        // Tenta detectar e formatar datas ISO
        if (is_string($value)) {
            // Data ISO completa: 2025-11-10T04:00:00.000000Z
            if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $value)) {
                try {
                    return Carbon::parse($value)->format('d/m/Y H:i:s');
                } catch (\Exception $e) {
                    // Continua para outras verificações
                }
            }

            // Data no formato Y-m-d: 2025-11-10
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                try {
                    return Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
                } catch (\Exception $e) {
                    // Continua para outras verificações
                }
            }

            // Data e hora MySQL: 2025-11-10 04:00:00
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
                try {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y H:i:s');
                } catch (\Exception $e) {
                    // Continua para outras verificações
                }
            }
        }

        // Para booleanos
        if (is_bool($value)) {
            return $value ? 'Sim' : 'Não';
        }

        // Para outros valores, retorna como string
        return (string) $value;
    }

}
