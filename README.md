# Laravel Cookie Consent

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stevecreekmore/cookies.svg?style=flat-square)](https://packagist.org/packages/stevecreekmore/cookies)
[![Tests](https://img.shields.io/github/actions/workflow/status/stevecreekmore/cookies/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/stevecreekmore/cookies/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/stevecreekmore/cookies.svg?style=flat-square)](https://packagist.org/packages/stevecreekmore/cookies)

A **GDPR-compliant** Laravel package for managing cookie consent with category-based consent management, script blocking, consent logging, and easy withdrawal.

## Features

- ✅ **GDPR Compliant** - Meets all major GDPR requirements
- 🚫 **Script Blocking** - Prevents non-consented scripts from loading
- 📝 **Consent Logging** - Database audit trail of all consent actions
- 🔄 **Easy Withdrawal** - Users can change their mind anytime
- 📊 **Detailed Cookie Information** - Show specific cookies, purposes, and durations
- 🎨 **Fully Customizable** - Themes, positioning, and text
- 🔌 **Blade Directives** - Easy integration with existing code
- 🌐 **Event System** - React to consent changes in JavaScript
- 📱 **Responsive Design** - Works on all devices

## GDPR Compliance

This package helps you comply with GDPR by:

1. **Blocking Non-Essential Cookies** - Scripts are blocked until consent is given
2. **Granular Consent** - Users can choose specific cookie categories
3. **Detailed Information** - Shows cookie names, purposes, durations, and providers
4. **Easy Withdrawal** - Floating button to change preferences anytime
5. **Consent Logging** - Audit trail stored in database (proof of consent)
6. **Privacy Policy Links** - Direct links to your policies
7. **Opt-in by Default** - No pre-checked boxes (except necessary cookies)


## Installation

Install the package via composer:

```bash
composer require stevecreekmore/cookies
```

The package will automatically register its service provider.

### Publish Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=cookies-config
```

### Publish Migrations (for consent logging)

```bash
php artisan vendor:publish --tag=cookies-migrations
php artisan migrate
```

### Publish Views (Optional)

If you want to customize the banner appearance:

```bash
php artisan vendor:publish --tag=cookies-views
```

### Add Middleware

Add the middleware to your `bootstrap/app.php` (Laravel 11+):

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \Stevecreekmore\Cookies\Middleware\AppendCookieConsentToResponse::class,
    ]);
})
```

Or for Laravel 10 and below, add to `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \Stevecreekmore\Cookies\Middleware\AppendCookieConsentToResponse::class,
    ],
];
```

## Configuration

### Define Your Cookies

Edit `config/cookies.php` to define the specific cookies your site uses:

```php
'categories' => [
    'necessary' => [
        'enabled' => true,
        'required' => true,
        'label' => 'Necessary',
        'description' => 'These cookies are essential for the website to function properly.',
        'cookies' => [
            'session' => [
                'name' => 'laravel_session',
                'purpose' => 'Maintains user session state',
                'duration' => '2 hours',
                'provider' => 'This website',
            ],
            'csrf' => [
                'name' => 'XSRF-TOKEN',
                'purpose' => 'Security token to prevent cross-site request forgery',
                'duration' => '2 hours',
                'provider' => 'This website',
            ],
        ],
    ],
    'analytics' => [
        'enabled' => true,
        'required' => false,
        'label' => 'Analytics',
        'description' => 'These cookies help us understand how visitors interact with our website.',
        'cookies' => [
            'google_analytics' => [
                'name' => '_ga, _gid, _gat',
                'purpose' => 'Used to distinguish users and sessions for analytics',
                'duration' => '2 years (_ga), 24 hours (_gid)',
                'provider' => 'Google LLC',
            ],
        ],
    ],
    // Add more categories...
],
```

### Set Policy URLs

```php
'policy_url' => env('COOKIE_PRIVACY_POLICY_URL', '/privacy-policy'),
'cookie_policy_url' => env('COOKIE_POLICY_URL', '/cookie-policy'),
```

Or in your `.env`:

```env
COOKIE_CONSENT_ENABLED=true
COOKIE_CONSENT_LOG=true
COOKIE_PRIVACY_POLICY_URL=/privacy-policy
COOKIE_POLICY_URL=/cookie-policy
```

## Usage

### Blocking Scripts Until Consent

The most important GDPR feature - scripts won't load until the user consents:

#### Method 1: Blade Directive (Recommended)

```blade
@cookieConsentScript('analytics')
    // Google Analytics
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-XXXXX-Y', 'auto');
    ga('send', 'pageview');
@endCookieConsentScript
```

#### Method 2: Manual Script Blocking

```html
<script type="text/plain" data-cookie-consent="marketing">
    // Facebook Pixel code
    !function(f,b,e,v,n,t,s){/* ... */}
</script>
```

The script will automatically be activated once the user consents to that category.

### Checking Consent in PHP

```php
use Stevecreekmore\Cookies\Facades\Cookies;

// Check specific category
if (Cookies::hasConsent('analytics')) {
    // Load analytics
}

// Get all consented categories
$categories = Cookies::getConsent();
// Returns: ['necessary', 'analytics']

// Check if any consent given
if (Cookies::hasGivenConsent()) {
    // User has interacted with banner
}

// Check if all categories accepted
if (Cookies::hasAcceptedAll()) {
    // User clicked "Accept All"
}
```

### Blade Conditional Directive

```blade
@cookieConsent('marketing')
    <script src="https://connect.facebook.net/en_US/fbevents.js"></script>
@endcookieConsent

@cookieConsent('analytics')
    <!-- Google Analytics tracking -->
@endcookieConsent
```

### JavaScript Event Listener

React to consent changes in your JavaScript:

```javascript
window.addEventListener('cookieConsentChanged', function(event) {
    const { categories, action, id } = event.detail;

    console.log('Consent action:', action); // 'accept_all', 'reject_all', or 'custom'
    console.log('Consented categories:', categories);
    console.log('Consent ID:', id);

    // Load scripts dynamically
    if (categories.includes('analytics')) {
        initializeAnalytics();
    }

    if (categories.includes('marketing')) {
        initializeMarketing();
    }
});
```

## GDPR Compliance Features

### 1. Consent Logging (Audit Trail)

All consent actions are logged to the database:

```php
use Stevecreekmore\Cookies\Models\CookieConsentLog;

// Get consent history for a user
$history = CookieConsentLog::where('cookie_id', $consentId)
    ->latest()
    ->get();

// Each log contains:
// - cookie_id (unique user identifier)
// - consented_categories (JSON array)
// - ip_address
// - user_agent
// - action (accept_all, reject_all, custom, withdraw)
// - timestamp
```

### 2. Consent Withdrawal

A floating button appears after consent is given, allowing users to:
- Change their preferences
- Withdraw consent completely
- View what they previously accepted

This is a GDPR requirement - withdrawal must be as easy as giving consent.

### 3. Detailed Cookie Information

Users can click "View Cookie Details" to see a table with:
- Cookie names
- Purpose of each cookie
- Duration/expiry
- Provider (first-party or third-party)

### 4. Log Retention

Control how long consent logs are kept (GDPR requires keeping records):

```php
'log_retention_days' => 1095, // 3 years (default)
```

Clean up old logs:

```php
use Stevecreekmore\Cookies\Models\CookieConsentLog;

// Manually clean up
CookieConsentLog::cleanupOldLogs();
```

Or schedule it in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        \Stevecreekmore\Cookies\Models\CookieConsentLog::cleanupOldLogs();
    })->weekly();
}
```

## Customization

### Styling

Customize appearance in `config/cookies.php`:

```php
'styling' => [
    'position' => 'bottom', // bottom, top, center
    'theme' => 'dark', // light, dark
],
```

### Custom Text

Change all text and labels:

```php
'text' => [
    'title' => 'We Value Your Privacy',
    'description' => 'Your custom description...',
    'accept_all' => 'Accept All',
    'reject_all' => 'Only Essential',
    // ... more text options
],
```

### Custom Banner View

After publishing views, edit `resources/views/vendor/cookies/banner.blade.php` for complete control.

### Disable Consent Logging

If you don't need database logging:

```env
COOKIE_CONSENT_LOG=false
```

### Hide Settings Button

```php
'show_settings_button' => false,
```

## Testing

This package comes with a comprehensive test suite using Pest.

### Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage
composer test-coverage

# Run specific test file
vendor/bin/pest tests/Feature/CookieConsentTest.php

# Run tests with detailed output
vendor/bin/pest --verbose
```

### Test Coverage

The package includes 80+ test cases covering:

- **CookieConsent Class** - All consent checking methods
- **ConsentLogger Service** - Database logging functionality
- **Middleware** - Banner injection and HTML response handling
- **CookieConsentLog Model** - Database operations and cleanup
- **ConsentController** - API endpoint validation
- **Blade Directives** - Template rendering and conditional display
- **Facade** - All facade methods
- **Architecture** - Code quality and security standards

### Requirements for Testing

The test suite requires:
- PHP ^8.4
- Laravel ^12.0
- SQLite (for in-memory testing)

All dependencies are automatically installed via `composer install`.

## Best Practices for GDPR Compliance

1. **Document All Cookies** - Fill in the `cookies` array for each category with specific cookie details
2. **Update Your Privacy Policy** - Include information about cookie usage
3. **Block All Non-Essential Scripts** - Use the Blade directive or manual blocking
4. **Keep Consent Logs** - Enable database logging for proof of consent
5. **Regular Audits** - Review what cookies your site actually sets
6. **Honor Withdrawals** - The floating button makes this easy
7. **Third-Party Cookies** - List all third-party providers clearly

## API Reference

### Facade Methods

```php
Cookies::hasConsent(string $category): bool
Cookies::getConsent(): array
Cookies::hasGivenConsent(): bool
Cookies::hasAcceptedAll(): bool
Cookies::getEnabledCategories(): array
Cookies::getRequiredCategories(): array
```

### Blade Directives

```blade
@cookieConsentScript('category')
    // Your script here
@endCookieConsentScript

@cookieConsent('category')
    <!-- Your content -->
@endcookieConsent
```

### JavaScript Events

```javascript
// Consent changed
window.addEventListener('cookieConsentChanged', function(event) {
    // event.detail contains: { categories, action, id }
});
```

## Troubleshooting

### Scripts Not Loading After Consent

Make sure you're using `type="text/plain"` and the `data-cookie-consent` attribute:

```html
<script type="text/plain" data-cookie-consent="analytics">
    // Your script
</script>
```

### Banner Not Showing

1. Check middleware is added to web routes
2. Ensure `COOKIE_CONSENT_ENABLED=true` in `.env`
3. Check if consent cookie already exists (delete it to test)

### Consent Not Being Logged

1. Run migrations: `php artisan migrate`
2. Check `COOKIE_CONSENT_LOG=true` in config
3. Ensure CSRF token is present on the page: `<meta name="csrf-token" content="{{ csrf_token() }}">`

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/stevecreekmore/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Steven Creekmore](https://github.com/stevecreekmore)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
