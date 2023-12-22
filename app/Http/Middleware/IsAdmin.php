<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = app('firebase.auth')->getUser($request->session()->get('uid'));
        if ($user->customClaims['admin'])
            return $next($request);    
        else
            abort('403', 'You are not a Admin');
    }
}
