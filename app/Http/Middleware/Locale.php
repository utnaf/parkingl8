<?php

namespace Parking\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Locale
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
        $currentLocale = App::getLocale();


        if(session()->has('locale') && session()->get('locale') !== $currentLocale) {
            App::setLocale(session()->get('locale'));
        }

        return $next($request);
    }
}
