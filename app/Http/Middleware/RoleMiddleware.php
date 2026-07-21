<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Accepts one or more role names, e.g. role:writer or role:writer,admin
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next, string ...$roles)
    {
        $user = Auth::user();

        if (!$user || !$user->role) {
            abort(403);
        }

        $allowed = collect($roles)
            ->flatMap(fn (string $role) => explode(',', $role))
            ->map(fn (string $role) => trim($role))
            ->filter()
            ->all();

        if (! in_array($user->role->name, $allowed, true)) {
            abort(403);
        }

        return $next($request);
    }
}
