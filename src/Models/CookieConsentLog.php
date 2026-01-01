<?php

namespace Stevecreekmore\Cookies\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CookieConsentLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'cookie_id',
        'consented_categories',
        'ip_address',
        'user_agent',
        'action',
    ];

    protected $casts = [
        'consented_categories' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the latest consent for a cookie ID
     */
    public static function getLatestConsent(string $cookieId): ?self
    {
        return static::where('cookie_id', $cookieId)
            ->latest()
            ->first();
    }

    /**
     * Clean up old consent logs based on retention policy
     */
    public static function cleanupOldLogs(): int
    {
        $retentionDays = config('cookies.log_retention_days', 1095);

        return static::where('created_at', '<', now()->subDays($retentionDays))
            ->delete();
    }
}
