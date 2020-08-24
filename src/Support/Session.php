<?php

namespace Src\Support;

class Session
{
    public static function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function has(string $key)
    {
        return isset($_SESSION[$key]);
    }

    public static function get($name)
    {
        return $_SESSION[$name];
    }

    public static function destroy($name)
    {
        unset($_SESSION[$name]);
    }
}