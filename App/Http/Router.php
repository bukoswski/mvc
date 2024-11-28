<?php

namespace App\Http;

use App\Http\Request;
use \Closure;
use \Exception;

class Router
{
    private $url = '';
    private $prefix = '';
    private $routes = [];
    private $request;
    public function __construct($url)
    {
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }
    private function setPrefix()
    {
        $parseUrl = parse_url($this->url);

        $this->prefix = $parseUrl['path'] ?? '';
    }

    private function addRoute($method, $route, $params = [])
    {


        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params["controller"] = $value;
                unset($params[$key]);
                continue;
            }
        }

        $params['variables'] = [];

        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        };


        $parttenRoute = '/^' . str_replace('/', '\/', $route) . '$/';

        echo "<pre>";
        print_r($parttenRoute);
        echo "</pre>";
        $this->routes[$parttenRoute][$method] = $params;
    }
    public function get($route, $params = [])
    {
        return $this->addRoute('GET', $route, $params);
    }
    public function post($route, $params = [])
    {
        return $this->addRoute('POST', $route, $params);
    }
    public function put($route, $params = [])
    {
        return $this->addRoute('PUT', $route, $params);
    }
    public function delete($route, $params = [])
    {
        return $this->addRoute('DELETE', $route, $params);
    }

    private function getUri()
    {

        $uri = $this->request->getUri();



        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
        return end($xUri);
    }
    private function getRoute()
    {
        $uri = $this->getUri();


        $httpMethod = $this->request->getHttpMethod();

        foreach ($this->routes as $parttenRoute => $methods) {
            if (preg_match($parttenRoute, $uri)) {
                if ($methods[$httpMethod]) {
                    return $methods[$httpMethod];
                }
                throw new Exception("Metodo não permitido", 405);
            }
        }
        throw new Exception("Rota não encontrada", 404);
    }

    public function run()
    {
        try {

            //throw new Exception('Okk', 200);

            $route = $this->getRoute();
            echo "<pre>";
            print_r($route);
            echo "</pre>";

            if (!isset($route['controller'])) {
                throw new Exception("Rota não foi processada", 500);
            }

            $args = [];


            return call_user_func_array($route['controller'], $args);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}