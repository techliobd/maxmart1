<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Currency;
use App\Models\Setting;

class SetCurrency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get currency from session or default
        $currencyCode = session('currency', config('app.currency', 'USD'));

        // Verify currency exists in database
        $currency = Currency::where('code', $currencyCode)->first();

        if (!$currency) {
            $currency = Currency::where('is_default', true)->first() 
                ?? Currency::first() 
                ?? new Currency(['code' => 'USD', 'symbol' => '$', 'rate' => 1]);
            $currencyCode = $currency->code;
        }

        // Set currency in session
        session(['currency' => $currencyCode]);

        // Share currency with all views
        view()->share('currentCurrency', $currency);

        return $next($request);
    }
}
