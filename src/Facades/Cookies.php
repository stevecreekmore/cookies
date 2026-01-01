<?php

namespace Stevecreekmore\Cookies\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Stevecreekmore\Cookies\CookieConsent
 */
class Cookies extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cookie-consent';
    }
}
