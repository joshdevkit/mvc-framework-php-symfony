<?php

namespace App\Middleware;

use App\Auth\Auth;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use Closure;

class RedirectIfAuthenticated extends Middleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && ($request->getPathInfo() === '/login' || $request->getPathInfo() === '/register')) {
            return redirect('/');
        }

        return $next($request);
    }
}
