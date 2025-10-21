<?php

namespace App\Helpers;

use Carbon\Carbon;

class AuditHelper
{
    public static function formatValue($value)
    {
        if (is_null($value)) {
            return 'N/A';
        }

        // Tenta detectar e formatar datas ISO
        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $value)) {
            try {
                return Carbon::parse($value)->format('d/m/Y H:i:s');
            } catch (\Exception $e) {
                return $value;
            }
        }

        return $value;
    }
}
