<?php

namespace Src\Support;

use Src\Support\Session;

/**
 * Class Auth
 * @package Src\Models
 */
class Auth
{
    public static function admin(): ?\Src\Models\Admin
    {
        if (!Session::has("admin")) {
            return null;
        }

        return (new \Src\Models\Admin())->findById(Session::get("admin"));
    }

    public static function user(): ?\Src\Models\User
    {
        if (!Session::has("user")) {
            return null;
        }

        return (new \Src\Models\User())->findById(Session::get("user"));
    }

    public static function setSession($key, $value)
    {
        Session::set($key, $value);
    }

    public static function destroySession($key)
    {
        Session::destroy($key);
    }
}

?>