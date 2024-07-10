<?php

namespace App\Route;

use BadMethodCallException;
use InvalidArgumentException;

class RouteRegistrar
{
    protected static array $routes = [];
    protected static array $currentGroupMiddleware = [];

    /**
     * Define a GET route.
     *
     * @param string $uri
     * @param array $handler
     * @return void
     */
    public static function get(string $uri, array $handler): void
    {
        self::addRoute('GET', $uri, $handler);
    }

    /**
     * Define a POST route.
     *
     * @param string $uri
     * @param array $handler
     * @return void
     */
    public static function post(string $uri, array $handler): void
    {
        self::addRoute('POST', $uri, $handler);
    }

    /**
     * Add middleware to the current group.
     *
     * @param string|array $aliases
     * @return self
     */
    public static function middleware($aliases): self
    {
        self::$currentGroupMiddleware = (array) $aliases;
        return new self();
    }

    /**
     * Register routes within a group.
     *
     * @param \Closure $callback
     * @return void
     */
    public static function group(\Closure $callback): void
    {
        $callback();
        self::$currentGroupMiddleware = [];
    }

    /**
     * Get all registered routes.
     *
     * @return array
     */
    public static function routes(): array
    {
        return self::$routes;
    }

    /**
     * Add a route to the collection.
     *
     * @param string $method
     * @param string $uri
     * @param array $handler
     * @return void
     */
    protected static function addRoute(string $method, string $uri, array $handler): void
    {
        if (!empty(self::$currentGroupMiddleware)) {
            $handler['middleware'] = self::$currentGroupMiddleware;
        }

        self::$routes[] = [$method, $uri, $handler];
    }
}
