<?php

namespace App\Http\Middleware;

use Closure;
use Flash;
use Illuminate\Http\Request;

class UserRolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $userParameter = $request->route()->parameter('user') ?? null;
        $user = session('user');

        if (!in_array($role, $user->roles)) {
            if ($userParameter) {
                if ($user->id != $userParameter) {
                    Flash::error('Permissão negada.');
                    return redirect(route('index'));
                }
            } else {
                Flash::error('Permissão negada.');
                return redirect(route('index'));
            }
        }

        return $next($request);
    }
}
