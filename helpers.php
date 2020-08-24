<?php

function pwd_gen_hash($password)
{
    if (!empty(password_get_info($password)["algo"])) {
        return $password;
    }

    return password_hash($password, CONF_PASSWORD_ALGO, CONF_PASSWORD_OPTION);
}

function pwd_verify($password, $hash)
{
    return password_verify($password, $hash);
}

function pwd_rehash($hash)
{
    return password_needs_rehash($hash, CONF_PASSWORD_ALGO, CONF_PASSWORD_OPTION);
}

function parseArray($data, $field)
{
    $arr = array();
    foreach ($data as $item) {
        $arr[] = $item->$field;
    }
    return $arr;
}

function auth($guard = "users")
{
    switch ($guard) {
        case "admins":
            return isset($_SESSION["userLoggedAdmin"]) ? $_SESSION["userLoggedAdmin"] : null;
        default:
            return isset($_SESSION["userLogged"]) ? $_SESSION["userLogged"] : null;
    }
}

function image($image = null)
{
    if ($image) {
        return BASE_URL . "assets/images/{$image}";
    }
    return null;
}

function media($image = null)
{
    if ($image) {
        return BASE_URL . "media/{$image}";
    }
    return null;
}

function asset($path = null)
{
    if ($path) {
        return BASE_URL . "assets/{$path}";
    }
    return null;
}

function url($path = null)
{
    if ($path) {
        return BASE_URL . "{$path}";
    }

    return $path;
}

function back()
{
    return ($_SERVER["HTTP_REFERER"] ?? BASE_URL);
}

function dd(...$value)
{
    print("<pre>".print_r($value,true)."</pre>");
    die;
}

function check_url()
{
    return [
        "admin",
        "admin/home",
        "admin/logout",
        "admin/login",
        "admin/profile",
        "admin/update",
    ];
}

function calc_percent($a, $b)
{
    return (($a / $b) * 100);
}

function str_price($price)
{
    return number_format(!empty($price) ? $price : 0, 2, ",", ".");
}

function str_price_db($price)
{
    return str_replace([".",","],["","."], !empty($price) ? $price : 0);
}

function checked($cond)
{
    return $cond ? 'checked="checked"' : "";
}

function selected($cond)
{
    return $cond ? 'selected="selected"' : "";
}

function getError($id)
{
    $errors = [
        1 => "E-mail  e/ou senha inválidos",
        2 => "Não foi possível processar o pagamento, tente novamente.",
    ];

    return $errors[$id];
}

function setMenuActive($path = [])
{
    $url = isset($_GET["url"]) ? $_GET["url"] : "home";

    if (count(explode("/", $_GET["url"])) >= 4) {
        $arr = explode("/", $_GET["url"]);
        $url = "{$arr[0]}/{$arr[1]}/{$arr[2]}";
    }

    if (count($path) && in_array($url, $path)) {
        return "active";
    }
    return "";
}

function hasPermission($slug)
{
    if (in_array($slug, auth("admins")->permissions)) {
        return true;
    }
    return false;
}

function str_slug($string)
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

    $slug = str_replace(["-----","----", "---", "--"], "-",
        str_replace(" ", "-",
            trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
        )
    );
    return $slug;
}

function setFlashMessage($status = "info", $messages = [])
{
    \Src\Support\Session::set("errors", ["status" => $status, "messages" => $messages]);
    return true;
}

function flashMessage($type = "errors")
{
    /**
     * Example
     * Session::set("errors", ["status" => "danger", "messages" => ["teste1","teste2","teste3"]]);
     */
    if (\Src\Support\Session::has($type)) {
        $flash = \Src\Support\Session::get($type);
        \Src\Support\Session::destroy($type);
        return $flash;
    }
    return null;
}