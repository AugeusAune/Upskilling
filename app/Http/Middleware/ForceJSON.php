<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceJSON
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
        if($request->header('accept') !== 'application/json') {
            $request->headers->set('accept', 'application/json');
        }
        return $next($request);
    }
}
