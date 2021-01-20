<?php

namespace Src\Router;

class RouteCollection
{
    protected $route_get = [];
    protected $route_post = [];
    protected $route_put = [];
    protected $route_delete = [];
    protected $route_names = [];

    public function add($request_type, $pattern, $callback)
    {
        switch ($request_type) {
            case 'get':
                return $this->addGet($pattern, $callback);
                break;
            case 'post':
                return $this->addPost($pattern, $callback);
                break;
            case 'put':
                return $this->addPut($pattern, $callback);
                break;
            case 'delete':
                return $this->addDelete($pattern, $callback);
                break;
            default:
                throw new \Exception('Method not allowed or not implemented');
        }
    }

    public function where($request_type, $pattern)
    {
        switch ($request_type) {
            case 'get':
                return $this->findGet($pattern);
                break;
            case 'post':
                return $this->findPost($pattern);
                break;
            case 'put':
                return $this->findPut($pattern);
                break;
            case 'delete':
                return $this->findDelete($pattern);
                break;
            default:
                throw new \Exception('Method not allowed or not implemented');
                break;
        }
    }

    public function parseURI($uri)
    {
        return implode('/', array_filter(explode('/', $uri)));
    }

    protected function definePattern($pattern)
    {
        $pattern = implode('/', array_filter(explode('/', $pattern)));
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';

        if (preg_match("/\{[A-Za-z0-9\_\-]{1,}\}/", $pattern)) {
            // old
            //$pattern = preg_replace("/\{[A-Za-z0-9\_\-]{1,}\}/", "[A-Za-z0-9]{1,}", $pattern);
            // to allow hifen (-) to slug
            $pattern = preg_replace("/\{[A-Za-z0-9\_\-]{1,}\}/", "[A-Za-z0-9-]{1,}", $pattern);
        }

        return $pattern;
    }

    protected function addGet($pattern, $callback)
    {
        if (is_array($pattern)) {
            $settings = $this->parsePattern($pattern);
            $pattern = $settings['set'];
        } else {
            $settings = [];
        }

        $values = $this->toMap($pattern);
        $this->route_get[$this->definePattern($pattern)] = [
            'callback' => $callback,
            'values' => $values,
            'namespace' => $settings['namespace'] ?? null
        ];

        if (isset($settings['as'])) {
            $this->route_names[$settings['as']] = $pattern;
        }
        return $this;
    }

    protected function addPost($pattern, $callback)
    {
        if (is_array($pattern)) {
            $settings = $this->parsePattern($pattern);
            $pattern = $settings['set'];
        } else {
            $settings = [];
        }

        $values = $this->toMap($pattern);
        $this->route_post[$this->definePattern($pattern)] = [
            'callback' => $callback,
            'values' => $values,
            'namespace' => $settings['namespace'] ?? null
        ];

        if (isset($settings['as'])) {
            $this->route_names[$settings['as']] = $pattern;
        }
        return $this;
    }

    protected function addPut($pattern, $callback)
    {
        if (is_array($pattern)) {
            $settings = $this->parsePattern($pattern);
            $pattern = $settings['set'];
        } else {
            $settings = [];
        }

        $values = $this->toMap($pattern);
        $this->route_put[$this->definePattern($pattern)] = [
            'callback' => $callback,
            'values' => $values,
            'namespace' => $settings['namespace'] ?? null
        ];

        if (isset($settings['as'])) {
            $this->route_names[$settings['as']] = $pattern;
        }
        return $this;
    }

    protected function addDelete($pattern, $callback)
    {
        if (is_array($pattern)) {
            $settings = $this->parsePattern($pattern);
            $pattern = $settings['set'];
        } else {
            $settings = [];
        }

        $values = $this->toMap($pattern);
        $this->route_delete[$this->definePattern($pattern)] = [
            'callback' => $callback,
            'values' => $values,
            'namespace' => $settings['namespace'] ?? null
        ];

        if (isset($settings['as'])) {
            $this->route_names[$settings['as']] = $pattern;
        }
        return $this;
    }

    protected function findGet($pattern_sent)
    {
        $pattern_sent = $this->parseURI($pattern_sent);
        foreach ($this->route_get as $pattern => $callback) {
            if (preg_match($pattern, $pattern_sent, $pieces)) {
                return (object) ['callback' => $callback, 'uri' => $pieces];
            }
        }
        return false;
    }

    protected function findPost($pattern_sent)
    {
        $pattern_sent = $this->parseURI($pattern_sent);
        foreach ($this->route_post as $pattern => $callback) {
            if (preg_match($pattern, $pattern_sent, $pieces)) {
                return (object) ['callback' => $callback, 'uri' => $pieces];
            }
        }
        return false;
    }

    protected function findPut($pattern_sent)
    {
        $pattern_sent = $this->parseURI($pattern_sent);
        foreach ($this->route_put as $pattern => $callback) {
            if (preg_match($pattern, $pattern_sent, $pieces)) {
                return (object) ['callback' => $callback, 'uri' => $pieces];
            }
        }
        return false;
    }

    protected function findDelete($pattern_sent)
    {
        $pattern_sent = $this->parseURI($pattern_sent);
        foreach ($this->route_delete as $pattern => $callback) {
            if (preg_match($pattern, $pattern_sent, $pieces)) {
                return (object) ['callback' => $callback, 'uri' => $pieces];
            }
        }
        return false;
    }

    protected function strposarray(string $haystack, array $needles, int $offset = 0)
    {
        $result = false;
        if (strlen($haystack) > 0 && count($needles) > 0) {
            foreach ($needles as $element) {
                $result = strpos($haystack, $element, $offset);
                if ($result !== false) {
                    break;
                }
            }
        }
        return $result;
    }

    protected function toMap($pattern)
    {
        $result = [];
        $needles = ['{', '[', '(', "\\"];
        $pattern = array_filter(explode('/', $pattern));

        foreach ($pattern as $key => $element) {
            $found = $this->strposarray($element, $needles);

            if ($found !== false) {
                if (substr($element, 0, 1) === '{') {
                    $result[preg_filter('/([\{\}])/', '', $element)] = $key - 1;
                } else {
                    $index = 'value_' . !empty($result) ? count($result) + 1 : 1;
                    array_merge($result, [$index => $key - 1]);
                }
            }
        }
        return count($result) > 0 ? $result : false;
    }

    protected function parsePattern(array $pattern)
    {
        // define the pattern
        $result['set'] = $pattern['set'] ?? null;
        // allows route name settings
        $result['as'] = $pattern['as'] ?? null;
        // allows new namespace definition for controllers
        $result['namespace'] = $pattern['namespace'] ?? null;

        return $result;
    }

    public function isThereAnyHow($name)
    {
        return $this->route_names[$name] ?? false;
    }

    public function convert($pattern, $params)
    {
        if (!is_array($params)) {
            $params = array($params);
        }

        $positions = $this->toMap($pattern);
        if ($positions === false) {
            $positions = [];
        }
        $pattern = array_filter(explode('/', $pattern));

        if (count($positions) < count($pattern)) {
            $uri = [];
            foreach ($pattern as $key => $element) {
                if (in_array($key - 1, $positions)) {
                    $uri[] = array_shift($params);
                } else {
                    $uri[] = $element;
                }
            }
            return implode('/', array_filter($uri));
        }
        return false;
    }
}