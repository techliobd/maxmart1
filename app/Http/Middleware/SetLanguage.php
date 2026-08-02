<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Language;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get language from session or default
        $locale = session('locale', config('app.locale', 'en'));

        // Verify language exists in database
        $language = Language::where('code', $locale)->first();

        if (!$language) {
            $language = Language::where('is_default', true)->first() 
                ?? Language::first() 
                ?? new Language(['code' => 'en', 'name' => 'English', 'is_default' => true]);
            $locale = $language->code;
        }

        // Set application locale
        app()->setLocale($locale);

        // Set language in session
        session(['locale' => $locale]);

        // Share language with all views
        view()->share('currentLanguage', $language);

        return $next($request);
    }
}
