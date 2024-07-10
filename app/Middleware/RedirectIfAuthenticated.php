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
        if (Auth::check()) {
            $roles = Auth::get('role');
            if (in_array('admin', $roles)) {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/');
            }
        }


        return $next($request);
    }
}
