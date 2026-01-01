<?php

namespace Stevecreekmore\Cookies;

use Illuminate\Http\Request;

class CookieConsent
{
    public function __construct(
        protected Request $request
    ) {}

    /**
     * Check if the user has given consent for a specific category
     */
    public function hasConsent(string $category): bool
    {
        $consent = $this->getConsent();

        if (empty($consent)) {
            return false;
        }

        return in_array($category, $consent);
    }

    /**
     * Get all consented categories
     */
    public function getConsent(): array
    {
        $cookieName = config('cookies.cookie_name', 'cookie_consent');
        $consentCookie = $this->request->cookie($cookieName);

        if (! $consentCookie) {
            return [];
        }

        $consent = json_decode($consentCookie, true);

        if (! is_array($consent)) {
            return [];
        }

        // If the consent data has a 'categories' key, return that
        // Otherwise return the entire array for backwards compatibility
        return $consent['categories'] ?? $consent;
    }

    /**
     * Check if consent has been given (any response)
     */
    public function hasGivenConsent(): bool
    {
        $cookieName = config('cookies.cookie_name', 'cookie_consent');

        return $this->request->hasCookie($cookieName);
    }

    /**
     * Check if all optional categories have been accepted
     */
    public function hasAcceptedAll(): bool
    {
        $consent = $this->getConsent();
        $allCategories = array_keys(config('cookies.categories', []));

        return count(array_intersect($consent, $allCategories)) === count($allCategories);
    }

    /**
     * Get enabled categories from configuration
     */
    public function getEnabledCategories(): array
    {
        $categories = config('cookies.categories', []);

        return array_filter($categories, fn ($category) => $category['enabled'] ?? false);
    }

    /**
     * Get required categories from configuration
     */
    public function getRequiredCategories(): array
    {
        $categories = config('cookies.categories', []);

        return array_filter($categories, fn ($category) => $category['required'] ?? false);
    }
}
