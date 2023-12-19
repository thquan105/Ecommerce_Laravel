<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class IsSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = app('firebase.firestore')->database()->collection('user')->document($request->session()->get('uid'))->snapshot();
        if ($user->data()['seller'])
            return $next($request);    
        else
            abort('403', 'You are not a seller');
    }
}
