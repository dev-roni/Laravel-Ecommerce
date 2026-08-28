<?php
// app/Services/AuditService.php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    // ── সাধারণ event log ─────────────────────────
    public static function log(string  $event,?Model  $model     = null,array   $oldValues = [],array   $newValues = [],): void {
        try {
            AuditLog::create([
                'user_id'    => Auth::id(),
                'user_name'  => Auth::user()?->name,
                'user_role'  => Auth::user()?->role,
                'event'      => $event,
                'model'      => $model ? class_basename($model) : null,
                'model_id'   => $model?->id,
                'old_values' => empty($oldValues) ? null : $oldValues,
                'new_values' => empty($newValues) ? null : $newValues,
                'ip'         => Request::ip(),
                'user_agent' => Request::userAgent(),
                'url'        => Request::fullUrl(),
                'method'     => Request::method(),
            ]);
        } catch (\Exception $e) {
            // Log fail হলে main flow block করবে না
            \Log::error('AuditService error: ' . $e->getMessage());
        }
    }

    // ── Model-এর dirty fields ধরে automatically log ──
    public static function logModelChange(string $event,Model  $model,array  $trackedFields = []): void {
        $oldValues = [];
        $newValues = [];

        // কোন fields track করবো
        $fields = empty($trackedFields)
            ? $model->getDirty()
            : array_intersect_key(
                $model->getDirty(),
                array_flip($trackedFields)
              );

        foreach ($fields as $field => $newVal) {
            // Sensitive fields mask করো
            if (in_array($field, ['password', 'remember_token'])) {
                continue;
            }

            $oldValues[$field] = $model->getOriginal($field);
            $newValues[$field] = $newVal;
        }

        if (!empty($newValues)) {
            self::log($event, $model, $oldValues, $newValues);
        }
    }
}