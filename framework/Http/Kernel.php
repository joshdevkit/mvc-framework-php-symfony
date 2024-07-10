<?php

namespace App\Framework\Http;

use FastRoute\RouteCollector;
use App\Database\Database;
use App\Framework\Http\Response;
use App\Util\Helper;

class Kernel
{
    protected Database $database;

    protected $routeMiddleware = [
        'auth' => \App\Middleware\Authenticate::class,
        'redirectIfAuthenticated' => \App\Middleware\RedirectIfAuthenticated::class,
    ];

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function handle(Request $request): Response
    {
        error_log('Handling request: ' . $request->getMethod() . ' ' . $request->getPathInfo());

        $dispatcher = \FastRoute\simpleDispatcher(function (RouteCollector $routeCollector) {
            $routes = include BASE_PATH . '/routes/web.php';
            foreach ($routes as $route) {
                list($method, $uri, $handler, $middleware) = $route;
                $routeCollector->addRoute($method, $uri, [$handler, $middleware]);
                error_log('Route added: ' . $method . ' ' . $uri . ' with middleware: ' . implode(',', $middleware));
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPathInfo()
        );

        $status = $routeInfo[0];
        error_log('Route status: ' . $status);

        if ($status === \FastRoute\Dispatcher::FOUND) {
            [$handler, $middleware] = $routeInfo[1];
            [$controllerClass, $controllerMethod] = $handler;
            $routeArguments = $routeInfo[2] ?? [];

            error_log('Route matched: ' . $controllerClass . '::' . $controllerMethod);
            error_log('Middleware: ' . implode(', ', $middleware));

            // Handle middleware
            foreach ($middleware as $middlewareAlias) {
                if (isset($this->routeMiddleware[$middlewareAlias])) {
                    $middlewareClass = new $this->routeMiddleware[$middlewareAlias]();
                    error_log('Applying middleware: ' . $middlewareAlias);

                    $response = $middlewareClass->handle($request, function ($request) use ($controllerClass, $controllerMethod, $routeArguments) {
                        // Invoke the next handler (controller method)
                        $controller = new $controllerClass($this->database);
                        // Prepare arguments for the controller method call
                        $arguments = array_merge([$request], array_values($routeArguments));
                        $content = call_user_func_array([$controller, $controllerMethod], $arguments);

                        // Ensure content is not null before returning a Response
                        if ($content === null) {
                            throw new \RuntimeException('Controller method returned null content.');
                        }

                        if ($content instanceof Response) {
                            return $content; // Return the response directly
                        } else {
                            return new Response($content); // Wrap string content in a Response object
                        }
                    });

                    if ($response instanceof Response) {
                        return $response; // Return the response directly if middleware returns a redirect response or other Response
                    } else {
                        throw new \RuntimeException('Middleware must return an instance of Response.');
                    }
                }
            }

            // This block should handle the case where no middleware is applied to the route
            $controller = new $controllerClass($this->database);

            // Prepare arguments for the controller method call
            $arguments = array_merge([$request], array_values($routeArguments));

            $content = call_user_func_array([$controller, $controllerMethod], $arguments);

            // Ensure content is not null before returning a Response
            if ($content === null) {
                throw new \RuntimeException('Controller method returned null content.');
            }

            if ($content instanceof Response) {
                return $content;
            }

            if (is_string($content)) {
                return new Response($content);
            }

            throw new \RuntimeException('Controller methods must return a string or a Response object.');
        }

        return $this->notFoundResponse();
    }


    protected function notFoundResponse(): Response
    {
        $content = Helper::view('404', ['title' => 'Page not found']);
        return new Response($content, 404);
    }
}
