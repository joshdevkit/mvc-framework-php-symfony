<?php


namespace App\Route;

use Closure;

class Route
{
    protected static array $routes = [];
    protected static array $currentGroupMiddleware = [];

    public static function get(string $uri, array $handler): void
    {
        self::addRoute('GET', $uri, $handler);
    }

    public static function post(string $uri, array $handler): void
    {
        self::addRoute('POST', $uri, $handler);
    }

    public static function middleware($middlewares): self
    {
        self::$currentGroupMiddleware = (array) $middlewares;
        return new self();
    }

    public static function group(Closure $callback): void
    {
        $callback();
        self::$currentGroupMiddleware = [];
    }

    public static function routes(): array
    {
        return self::$routes;
    }

    protected static function addRoute(string $method, string $uri, array $handler): void
    {
        $middleware = self::$currentGroupMiddleware;
        self::$routes[] = [$method, $uri, $handler, $middleware];
    }
}
