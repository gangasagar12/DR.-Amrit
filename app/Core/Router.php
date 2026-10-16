<?php
namespace App\Core;

class Router {
    private array $routes = [];
    private $notFoundHandler = null;

    public function get(string $path, mixed $handler): void {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, mixed $handler): void {
        $this->addRoute('POST', $path, $handler);
    }

    public function any(string $path, mixed $handler): void {
        $this->addRoute('GET', $path, $handler);
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, mixed $handler): void {
        $normalizedPath = '/' . trim($path, '/');
        if ($normalizedPath !== '/') {
            $normalizedPath = rtrim($normalizedPath, '/');
        }
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $normalizedPath,
            'handler' => $handler
        ];
    }

    public function setNotFound(mixed $handler): void {
        $this->notFoundHandler = $handler;
    }

    public function dispatch(Request $request): void {
        $currentMethod = $request->method();
        $currentPath = $request->path();

        if ($currentPath !== '/') {
            $currentPath = rtrim($currentPath, '/');
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $currentMethod && $route['method'] !== 'ANY') {
                continue;
            }

            // Convert route pattern with parameters e.g. /blog/{slug} to regex
            $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#i';

            if (preg_match($pattern, $currentPath, $matches)) {
                $params = [];
                foreach ($matches as $key => $val) {
                    if (is_string($key)) {
                        $params[$key] = urldecode($val);
                    }
                }

                $this->executeHandler($route['handler'], $request, $params);
                return;
            }
        }

        // Not Found
        if ($this->notFoundHandler) {
            $this->executeHandler($this->notFoundHandler, $request, []);
        } else {
            http_response_code(404);
            View::render('public/404', ['title' => 'Page Not Found'], 'layouts/main');
        }
    }

    private function executeHandler(mixed $handler, Request $request, array $params): void {
        if (is_callable($handler)) {
            call_user_func_array($handler, [$request, $params]);
            return;
        }

        if (is_string($handler) && strpos($handler, '@') !== false) {
            list($controllerName, $action) = explode('@', $handler, 2);
            $fullClass = "\\App\\Controllers\\{$controllerName}";

            if (!class_exists($fullClass)) {
                throw new \Exception("Controller not found: {$fullClass}");
            }

            $controller = new $fullClass();

            if (!method_exists($controller, $action)) {
                throw new \Exception("Method {$action} not found on controller {$fullClass}");
            }

            call_user_func_array([$controller, $action], [$request, $params]);
            return;
        }

        throw new \Exception("Invalid route handler definition.");
    }
}
