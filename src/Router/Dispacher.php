<?php

namespace Src\Router;

class Dispacher
{
    public function dispach($callback, array $params = [], $namespace = "Src\\Controllers\\")
    {
        if (is_callable($callback['callback'])) {
            return call_user_func_array($callback['callback'], array_values($params));
        } elseif (is_string($callback['callback'])) {
            if (!!strpos($callback['callback'], '@') !== false) {

                if (!empty($callback['namespace'])) {
                    $namespace = $callback['namespace'];
                }

                $callback['callback'] = explode('@', $callback['callback']);
                $controller = $namespace.$callback['callback'][0];
                $method = $callback['callback'][1];

                try {

                    $rc = new \ReflectionClass($controller);

                    if ($rc->isInstantiable() && $rc->hasMethod($method)) {
                        return call_user_func_array([new $controller, $method], array_values($params));
                    } else {
                        throw new \Exception('Error dispach: controller not is istanciable or method not exists');
                    }

                } catch (\ReflectionException $e) {
                    throw new \Exception('Error dispach: ' . $e->getMessage());
                }
            }
        }
        throw new Exception('Error dispach: method not implemented');
    }
}