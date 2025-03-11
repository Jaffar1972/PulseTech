<?php

namespace Routes;

class Route
{
    private array $routes = [];

    public function register(string $path, callable|array $action)
    {
        $this->routes[$path] = $action;
    }
    
    public function resolve(string $uri): mixed
    {
        $path = explode('?', $uri)[0];

        foreach ($this->routes as $route => $action) {
            // Remplacer les paramètres dynamiques par une regexw
            $routePattern = preg_replace('#:([\w]+)#', '([\w]+)', $route);

            // Gestion des paramètres optionnels (par exemple :id?)
            $routePattern = preg_replace('#/:([\w]+)\?#', '(?:/([\w]+))?', $routePattern);
         

            $routePattern = "#^" . $routePattern . "$#";

            if (preg_match($routePattern, $path, $matches)) {
                // Retirer le premier élément (URL complète capturée)
                array_shift($matches);

                // Si l'action est callable
                if (is_callable($action)) {
                    return call_user_func_array($action, $matches);
                }

                // Si l'action est un tableau [Controller, method]
                if (is_array($action) && class_exists($action[0]) && method_exists($action[0], $action[1])) {
                    return call_user_func_array([new $action[0], $action[1]], $matches);
                }
            }
        }

        throw new \Exception('No route for this one');
    }
}