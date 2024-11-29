<?php

namespace App\Http;

use App\Http\Request;
use Closure;
use Exception;
use ReflectionFunction;


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

        if (preg_match_all($patternVariable, $route, $matches)) {;

            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        };


        $parttenRoute = '/^' . str_replace('/', '\/', $route) . '$/';


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
        // Obtém a URI atual
        $uri = $this->getUri();

        // Obtém o método HTTP atual
        $httpMethod = $this->request->getHttpMethod();

        // Itera sobre as rotas registradas
        foreach ($this->routes as $parttenRoute => $methods) {
            // Verifica se a URI corresponde ao padrão da rota
            if (preg_match($parttenRoute, $uri, $matches)) {
                // Verifica se o método HTTP é permitido para essa rota
                if (isset($methods[$httpMethod])) {
                    // Remove o primeiro índice do array de matches
                    unset($matches[0]);


                    // Associa as variáveis capturadas com seus valores
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);

                    // Adiciona o objeto Request aos dados da rota
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    // Retorna os dados da rota encontrada

                    return $methods[$httpMethod];
                }

                // Se o método não for permitido, lança uma exceção
                throw new Exception("Método não permitido", 405);
            }
        }

        // Se nenhuma rota for encontrada, lança uma exceção
        throw new Exception("Rota não encontrada", 404);
    }

    public function run()
    {
        try {
            // Obtém a rota processada
            $route = $this->getRoute();

            // Verifica se o controlador está definido
            if (!isset($route['controller'])) {
                throw new Exception("Rota não foi processada", 500);
            }

            // Prepara os argumentos para o controlador
            $args = [];
            $reflection = new ReflectionFunction($route['controller']);

            foreach ($reflection->getParameters() as $parameter) {
                // Obtém o nome do parâmetro esperado no controlador
                $name = $parameter->getName();


                // Verifica se o nome existe nas variáveis da rota
                if (isset($route['variables'][$name])) {
                    $args[] = $route['variables'][$name];
                } else {
                    // Adiciona `null` caso o parâmetro não seja encontrado
                    $args[] = null;
                }
            }

            // Executa o controlador com os argumentos processados
            return call_user_func_array($route['controller'], $args);
        } catch (Exception $e) {
            // Retorna uma resposta com o código e mensagem de erro
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}
