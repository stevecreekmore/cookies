<?php

namespace Stevecreekmore\Cookies;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Stevecreekmore\Cookies\Http\Controllers\ConsentController;
use Stevecreekmore\Cookies\Services\ConsentLogger;

class CookiesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/cookies.php',
            'cookies'
        );

        $this->app->singleton('cookie-consent', function ($app) {
            return new CookieConsent($app['request']);
        });

        $this->app->singleton(ConsentLogger::class, function ($app) {
            return new ConsentLogger($app['request']);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/cookies.php' => config_path('cookies.php'),
            ], 'cookies-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/cookies'),
            ], 'cookies-views');

            $this->publishes([
                __DIR__.'/../database/migrations/create_cookie_consent_logs_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_cookie_consent_logs_table.php'),
            ], 'cookies-migrations');
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cookies');
        $this->registerBladeDirectives();
        $this->registerBladeComponents();
        $this->registerRoutes();
    }

    protected function registerBladeDirectives(): void
    {
        // Directive for scripts that require consent
        Blade::directive('cookieConsentScript', function ($category) {
            return "<?php echo view('cookies::script-wrapper', ['category' => {$category}])->render(); ?>";
        });

        Blade::directive('endCookieConsentScript', function () {
            return "<?php echo '</script>'; ?>";
        });

        // Simple check directive
        Blade::if('cookieConsent', function ($category) {
            return app('cookie-consent')->hasConsent($category);
        });
    }

    protected function registerBladeComponents(): void
    {
        Blade::component('cookies::components.settings-link', 'cookie-settings-link');
    }

    protected function registerRoutes(): void
    {
        Route::prefix('api/cookie-consent')
            ->name('cookie-consent.')
            ->group(function () {
                Route::post('log', [ConsentController::class, 'log'])->name('log');
                Route::delete('withdraw', [ConsentController::class, 'withdraw'])->name('withdraw');
            });
    }
}
