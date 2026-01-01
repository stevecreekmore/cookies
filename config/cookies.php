<?php

return [
    /*
     * Enable or disable cookie consent globally
     */
    'enabled' => env('COOKIE_CONSENT_ENABLED', true),

    /*
     * The name of the cookie that stores consent preferences
     */
    'cookie_name' => 'cookie_consent',

    /*
     * Cookie lifetime in days
     */
    'cookie_lifetime' => 365,

    /*
     * Cookie categories that can be consented to
     */
    'categories' => [
        'necessary' => [
            'enabled' => true,
            'required' => true,
            'label' => 'Necessary',
            'description' => 'These cookies are essential for the website to function properly.',
            'cookies' => [
                // Example:
                // 'session_id' => [
                //     'name' => 'PHPSESSID',
                //     'purpose' => 'Maintains user session state',
                //     'duration' => '2 hours',
                //     'provider' => 'This website',
                // ],
            ],
        ],
        'analytics' => [
            'enabled' => true,
            'required' => false,
            'label' => 'Analytics',
            'description' => 'These cookies help us understand how visitors interact with our website.',
            'cookies' => [
                // Example:
                // 'google_analytics' => [
                //     'name' => '_ga, _gid',
                //     'purpose' => 'Used to distinguish users and sessions',
                //     'duration' => '2 years',
                //     'provider' => 'Google LLC',
                // ],
            ],
        ],
        'marketing' => [
            'enabled' => true,
            'required' => false,
            'label' => 'Marketing',
            'description' => 'These cookies are used to track visitors across websites for advertising purposes.',
            'cookies' => [
                // Example:
                // 'facebook_pixel' => [
                //     'name' => '_fbp',
                //     'purpose' => 'Used to deliver advertising',
                //     'duration' => '3 months',
                //     'provider' => 'Facebook',
                // ],
            ],
        ],
        'preferences' => [
            'enabled' => true,
            'required' => false,
            'label' => 'Preferences',
            'description' => 'These cookies allow the website to remember choices you make.',
            'cookies' => [
                // Example:
                // 'theme' => [
                //     'name' => 'user_theme',
                //     'purpose' => 'Remembers your theme preference',
                //     'duration' => '1 year',
                //     'provider' => 'This website',
                // ],
            ],
        ],
    ],

    /*
     * Styling options for the consent banner
     */
    'styling' => [
        'position' => 'bottom', // bottom, top, center
        'theme' => 'light', // light, dark
    ],

    /*
     * Consent banner text
     */
    'text' => [
        'title' => 'Cookie Consent',
        'description' => 'We use cookies to enhance your browsing experience, serve personalized content, and analyze our traffic. By clicking "Accept All", you consent to our use of cookies.',
        'accept_all' => 'Accept All',
        'accept_selected' => 'Accept Selected',
        'reject_all' => 'Reject All',
        'manage_preferences' => 'Manage Preferences',
        'save_preferences' => 'Save Preferences',
        'cookie_details' => 'Cookie Details',
        'view_details' => 'View Cookie Details',
    ],

    /*
     * Log consent to database for GDPR compliance audit trail
     */
    'log_consent' => env('COOKIE_CONSENT_LOG', true),

    /*
     * Number of days to keep consent logs (GDPR requires keeping records)
     */
    'log_retention_days' => 1095, // 3 years

    /*
     * Show floating "Cookie Settings" button after consent is given
     */
    'show_settings_button' => true,

    /*
     * Privacy policy and cookie policy URLs
     */
    'policy_url' => env('COOKIE_PRIVACY_POLICY_URL', '/privacy-policy'),
    'cookie_policy_url' => env('COOKIE_POLICY_URL', '/cookie-policy'),
];
