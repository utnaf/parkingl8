<?php
declare(strict_types=1);

namespace Parking\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/** @codeCoverageIgnore */
class Role {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, string $role) {
        if (!(Auth::check() && Auth::user()->isRole($role))) {
            abort(Response::HTTP_UNAUTHORIZED, 'Sorry, you are not authorized to perform this action.');
        }

        return $next($request);
    }
}
