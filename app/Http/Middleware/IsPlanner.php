<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsPlanner
{
    public function handle(Request $request, Closure $next)
    {
        $role = optional(auth()->user()->role)->role_name;
        if (!auth()->user() || !in_array($role, ['admin', 'planner'])) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}
