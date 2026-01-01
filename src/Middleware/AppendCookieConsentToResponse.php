<?php

namespace Stevecreekmore\Cookies\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppendCookieConsentToResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldInjectBanner($response)) {
            return $response;
        }

        return $this->injectConsentBanner($response);
    }

    protected function shouldInjectBanner(Response $response): bool
    {
        if (! config('cookies.enabled', true)) {
            return false;
        }

        $contentType = $response->headers->get('Content-Type', '');

        return str_contains($contentType, 'text/html');
    }

    protected function injectConsentBanner(Response $response): Response
    {
        $content = $response->getContent();

        if (! is_string($content)) {
            return $response;
        }

        $banner = view('cookies::banner')->render();
        $content = str_replace('</body>', $banner.'</body>', $content);

        $response->setContent($content);

        return $response;
    }
}
