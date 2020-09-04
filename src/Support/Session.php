<?php

namespace Src\Support;

/**
 * Class Session
 * @package Src\Support
 */
class Session
{
    /**
     * @param $name
     * @param $value
     */
    public static function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function has(string $key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        return $_SESSION[$name];
    }

    /**
     * @param $name
     */
    public static function destroy($name)
    {
        unset($_SESSION[$name]);
    }
}