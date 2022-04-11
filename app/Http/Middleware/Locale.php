<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Locale
{
    protected $languages;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->languages = ['en', 'pt'];

        if (Auth::check()) {
            App::setLocale(Auth::user()->lang);
            Carbon::setLocale(Auth::user()->lang);
        } else {
            $lang = data_get($_COOKIE, 'lang', 'en');
            $lang = in_array($lang, $this->languages) ? $lang : 'en';

            App::setLocale($lang);
            Carbon::setLocale($lang);
        }

        return $next($request);
    }
}
