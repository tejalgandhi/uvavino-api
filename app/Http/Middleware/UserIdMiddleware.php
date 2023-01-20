<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->user()){
            $wine_user_id = $request->user()->id;
            $request->merge(['wine_user_id' =>$wine_user_id]);
        }
        return $next($request);
    }
}
