<?php


namespace App\Middleware;

use App\Framework\Http\Request;
use App\Framework\Http\Response;

abstract class Middleware
{
    abstract public function handle(Request $request, \Closure $next): Response;
}