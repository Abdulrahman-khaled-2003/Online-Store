<?php

namespace App\Core;

use App\Core\Exceptions\FileNotFoundException;

class Router
{
    public $routes = [];

    public function add($method, $uri, $controllers)
    {
        $this->routes[] = [
            "method" => $method,
            "uri" => $uri,
            "controllers" => $controllers
        ];
    }

    public function get($uri, $controllers)
    {
        $this->add("GET", $uri, $controllers);
    }

    public function post($uri, $controllers)
    {
        $this->add("POST", $uri, $controllers);
    }

    public function delete($uri, $controllers)
    {
        $this->add("DELETE", $uri, $controllers);
    }

    public function patch($uri, $controllers)
    {
        $this->add("PATCH", $uri, $controllers);
    }

    public function put($uri, $controllers)
    {
        $this->add("PUT", $uri, $controllers);
    }

    public function route($method, $uri)
    {
        foreach ($this->routes as $routes) {

            if (strtoupper($method) === $routes['method']) {

                $pattern = preg_replace('/\{([^\/]+)\}/', '([^/]+)', $routes['uri']);

                $pattern = "#^" . $pattern . "$#";

                if (preg_match($pattern, $uri, $matches)) {

                    array_shift($matches);

                    [$className, $methodName] = stringToArray("::", $routes["controllers"]);

                    if (! file_exists(base_path("Http/Controllers/{$className}.php"))) {
                        throw new FileNotFoundException("{$className} Class Not Found!");
                    }

                    $className = "\App\Http\Controllers\\" . $className;

                    $matches[] = $_POST ?? $matches;

                    call_user_func_array([new $className, $methodName], $matches);
                    exit();
                }
            }
        }
        abort(404, "Not Found Page!");
    }
}
