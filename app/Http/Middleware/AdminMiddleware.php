<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->user()->roles != 'admin' && $request->user()->abilities != 'user:all'){
            return response()->json('access denied, User not authorized', 401);
        }
        return $next($request);
    }
}
