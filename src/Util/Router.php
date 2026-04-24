<?php

namespace KCPocket\Util;

class Router
{
    private static array $routes = [];

    public static function add(string $method, string $path, callable $callback): void
    {
        // Normalizar o path da rota: garantir que comece com / e não termine com / (exceto a raiz)
        $path = '/' . trim($path, '/');
        self::$routes[strtoupper($method)][$path] = $callback;
    }

    public static function get(string $path, callable $callback): void
    {
        self::add("GET", $path, $callback);
        self::add("HEAD", $path, $callback);
    }

    public static function post(string $path, callable $callback): void
    {
        self::add("POST", $path, $callback);
    }

    public static function dispatch(): void
    {
        $method = strtoupper($_SERVER["REQUEST_METHOD"]);
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        
        // Normalizar a URI da requisição
        $uri = '/' . trim($uri, '/');
        
        error_log("Router Dispatching: $method $uri");

        // 1. Tentar correspondência exata (mais rápido e seguro)
        if (isset(self::$routes[$method][$uri])) {
            call_user_func(self::$routes[$method][$uri]);
            return;
        }

        // 2. Tentar correspondência com parâmetros {id}
        foreach (self::$routes[$method] ?? [] as $routePath => $callback) {
            if (strpos($routePath, '{') !== false) {
                $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_\-\.]+)', $routePath);
                $pattern = "#^" . $pattern . "$#";

                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    call_user_func_array($callback, $matches);
                    return;
                }
            }
        }

        // 3. Fallback 404
        error_log("Router 404: $method $uri");
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>A rota <strong>$method $uri</strong> não existe.</p>";
        exit();
    }
}
