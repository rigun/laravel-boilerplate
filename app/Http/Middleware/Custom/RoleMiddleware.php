<?php

namespace App\Http\Middleware\Custom;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = auth()->userOrFail();
        $userRole = $user->role_name;
        $roles = explode('|',$role);
        if(in_array($userRole,$roles)){
            return $next($request);
        }else{
            return response()->json(['msg' => 'Unauthorization Role', 'status' => 0, 'data' => []]);
        }
    }
}
