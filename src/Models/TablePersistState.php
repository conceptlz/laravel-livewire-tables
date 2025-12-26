<?php

namespace Rappasoft\LaravelLivewireTables\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TablePersistState extends Model
{
    protected $fillable = [
        'table_name',
        'user_id',
        'session_id',
        'state_data',
        'expires_at',
    ];

    protected $casts = [
        'state_data' => 'array',
        'expires_at' => 'datetime',
    ];

    public static function saveState(string $tableName, array $stateData, int $expiryDays = 365): void
    {
        $expiresAt = now()->addDays($expiryDays);
        
        $attributes = [
            'table_name' => $tableName,
            'user_id' => Auth::id(),
        ];

        static::updateOrCreate(
            $attributes,
            [
                'state_data' => $stateData,
                'expires_at' => $expiresAt,
            ]
        );
    }

    public static function getState(string $tableName): ?array
    {
        $query = static::where('table_name', $tableName)
            ->where('expires_at', '>', now());

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', session()->getId())
                  ->whereNull('user_id');
        }

        $state = $query->first();

        return $state ? $state->state_data : null;
    }

    public static function cleanupExpired(): int
    {
        return static::where('expires_at', '<=', now())->delete();
    }

    public static function clearState(string $tableName): void
    {
        $query = static::where('table_name', $tableName);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', session()->getId())
                  ->whereNull('user_id');
        }

        $query->delete();
    }
}
