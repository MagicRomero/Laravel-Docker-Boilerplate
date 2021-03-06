<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (isset($user) && $user->lang !== App::getLocale()) {
            $app_languages = config('languages');

            $locale = in_array($user->lang, $app_languages['available']) ?
                $user->lang :
                config('app.locale') || config('app.fallback_locale');

            switch_app_language($app_languages, $locale);
        }

        return $next($request);
    }
}
