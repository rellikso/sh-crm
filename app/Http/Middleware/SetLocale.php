<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check query parameter (for iframe web routes)
        // 2. Check HTTP header (for AJAX API routes)
        // 3. Fallback to default app configuration
        $locale = $request->query('lang')
            ?? $request->header('Accept-Language')
            ?? config('app.locale');

        // Basic safety check: allow only within allowed locales
        if (in_array($locale, config('app.available_locales'))) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}