<?php

namespace Stevecreekmore\Cookies\Tests;

use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Stevecreekmore\Cookies\CookiesServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->runMigrations();
    }

    protected function getPackageProviders($app): array
    {
        return [
            CookiesServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Set up cookie consent config
        $app['config']->set('cookies.enabled', true);
        $app['config']->set('cookies.cookie_name', 'cookie_consent');
        $app['config']->set('cookies.cookie_lifetime', 365);
        $app['config']->set('cookies.log_consent', true);
        $app['config']->set('cookies.categories', [
            'necessary' => [
                'enabled' => true,
                'required' => true,
                'label' => 'Necessary',
                'description' => 'Essential cookies',
            ],
            'analytics' => [
                'enabled' => true,
                'required' => false,
                'label' => 'Analytics',
                'description' => 'Analytics cookies',
            ],
        ]);
    }

    protected function runMigrations(): void
    {
        // Drop the table if it exists to ensure clean state
        if (Schema::hasTable('cookie_consent_logs')) {
            Schema::drop('cookie_consent_logs');
        }

        $migration = include __DIR__.'/../database/migrations/create_cookie_consent_logs_table.php.stub';
        $migration->up();
    }
}
