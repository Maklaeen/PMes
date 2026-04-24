<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireRole
{
    /**
     * @param  array<int, string>  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();
        $roleName = optional($user?->role)->role_name;

        if (!$user || !$roleName || (!empty($roles) && !in_array($roleName, $roles, true))) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
