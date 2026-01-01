<?php

namespace Stevecreekmore\Cookies\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stevecreekmore\Cookies\Models\CookieConsentLog;

class ConsentLogger
{
    protected ?string $generatedCookieId = null;

    public function __construct(
        protected Request $request
    ) {}

    /**
     * Log a consent action
     */
    public function log(array $categories, string $action = 'custom'): ?CookieConsentLog
    {
        if (! config('cookies.log_consent', true)) {
            return null;
        }

        $cookieId = $this->getCookieId();

        return CookieConsentLog::create([
            'cookie_id' => $cookieId,
            'consented_categories' => $categories,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'action' => $action,
        ]);
    }

    /**
     * Get or create a unique cookie ID for this user
     */
    protected function getCookieId(): string
    {
        $cookieName = config('cookies.cookie_name', 'cookie_consent');
        $consentCookie = $this->request->cookie($cookieName);

        if ($consentCookie) {
            $data = json_decode($consentCookie, true);
            if (isset($data['id'])) {
                return $data['id'];
            }
        }

        // Generate once and cache for this request
        if ($this->generatedCookieId === null) {
            $this->generatedCookieId = Str::uuid()->toString();
        }

        return $this->generatedCookieId;
    }

    /**
     * Get consent history for a cookie ID
     */
    public function getHistory(string $cookieId, int $limit = 10): array
    {
        return CookieConsentLog::where('cookie_id', $cookieId)
            ->latest()
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
