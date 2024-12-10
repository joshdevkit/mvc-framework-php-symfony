<?php

namespace App\Middleware;

use App\Auth\Auth;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Util\Redirect;
use Closure;

class Authenticate extends Middleware
{
    public function handle(Request $request, \Closure $next, ...$params): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (str_starts_with($request->getPathInfo(), '/admin') && !in_array('admin', Auth::get('role'))) {
            return redirect('/');
        }

        return $next($request);
    }
}
