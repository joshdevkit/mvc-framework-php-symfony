<?php

namespace App\Middleware;

use App\Framework\Http\Request;
use App\Framework\Http\Response;
use Closure;

interface MiddlewareInterface
{
    public function handle(Request $request, Closure $next): Response;
}
