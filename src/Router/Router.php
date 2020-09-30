<?php

/**
 *
 * Esta classe Router foi digitada linha a linha seguindo o tutorial do
 * Alexandre Bezerra Barbosa (https://alexandrebbarbosa.wordpress.com/)
 * ensinando a criar um sistema de rotas do zero com php utilizando as melhores práticas do mercado,
 * este sistema de rotas eh parecido com o mesmo sistema de rotas que alguns frameworks como Laravel, utilizam.
 *
 * Links dos artigos
 *
 * https://alexandrebbarbosa.wordpress.com/2019/04/17/phpconstruir-um-sistema-de-rotas-para-mvc-primeira-parte/
 * https://alexandrebbarbosa.wordpress.com/2019/04/19/phpconstruir-um-sistema-de-rotas-para-mvc-segunda-parte/
 * https://alexandrebbarbosa.wordpress.com/2019/04/23/phpconstruir-um-sistema-de-rotas-para-mvc-terceira-parte/
 *
 * Neste sistema fiz algumas adaptações para necessidade do meu sistema.
 */

namespace Src\Router;

class Router
{
    protected $route_collection;

    public function __construct()
    {
        $this->route_collection = new RouteCollection;
        $this->dispacher = new Dispacher;
    }

    public function get($pattern, $callback)
    {
        $this->route_collection->add('get', $pattern, $callback);
        return $this;
    }

    public function post($pattern, $callback)
    {
        $this->route_collection->add('post', $pattern, $callback);
        return $this;
    }

    public function put($pattern, $callback)
    {
        $this->route_collection->add('put', $pattern, $callback);
        return $this;
    }

    public function delete($pattern, $callback)
    {
        $this->route_collection->add('delete', $pattern, $callback);
        return $this;
    }

    public function find($request_type, $pattern)
    {
        return $this->route_collection->where($request_type, $pattern);
    }

    protected function dispach($route, $params, $namespace = "Src\\Controllers\\")
    {
        return $this->dispacher->dispach($route->callback, $params, $namespace);
    }

    protected function notFound()
    {
        return header("HTTP/1.0 404 Not Found", true, 404);
    }

    public function resolve($request)
    {
        $route = $this->find($request->method(), $request->uri());

        if ($route) {
            $params = $route->callback['values'] ? $this->getValues($request->uri(), $route->callback['values']) : [];
            return $this->dispach($route, $params);
        }

        return $this->notFound();
    }

    protected function getValues($pattern, $positions)
    {
        $result = [];
        $pattern = array_filter(explode('/', $pattern));

        foreach ($pattern as $key => $value) {
            if (in_array($key, $positions)) {
                $result[array_search($key, $positions)] = $value;
            }
        }
        return $result;
    }

    public function translate($name, $params)
    {
        $pattern = $this->route_collection->isThereAnyHow($name);

        if ($pattern) {
            $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
            $server = $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/';
            $uri = [];

            $request_uri = explode('/', $_SERVER['REQUEST_URI']);
            array_pop($request_uri);

            while (in_array("admin", $request_uri)) {
                array_pop($request_uri);
            }

            foreach (array_filter($request_uri) as $key => $value) {
                $uri[] = $value;
            }

            $uri = implode('/', array_filter($uri)) . '/';
            return $protocol . $server . $uri . $this->route_collection->convert($pattern, $params);
        }
        return false;
    }
}