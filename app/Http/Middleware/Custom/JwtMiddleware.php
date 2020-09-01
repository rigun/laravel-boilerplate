<?php

namespace App\Http\Middleware\Custom;

use Closure;

class JwtMiddleware
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
        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['msg' => 'unauthorized', 'status' => -1, 'data' => []], 403);
        }

        return $next($request);
    }
}
