<?php

/**
 * @param $password
 * @return false|string
 */
function pwd_gen_hash($password)
{
    if (!empty(password_get_info($password)["algo"])) {
        return $password;
    }

    return password_hash($password, CONF_PASSWORD_ALGO, CONF_PASSWORD_OPTION);
}

/**
 * @param $password
 * @param $hash
 * @return bool
 */
function pwd_verify($password, $hash)
{
    return password_verify($password, $hash);
}

/**
 * @param $hash
 * @return bool
 */
function pwd_rehash($hash)
{
    return password_needs_rehash($hash, CONF_PASSWORD_ALGO, CONF_PASSWORD_OPTION);
}

/**
 * @param $data
 * @param $field
 * @return array
 */
function parseArray($data, $field)
{
    $arr = array();
    foreach ($data as $item) {
        $arr[] = $item->$field;
    }
    return $arr;
}

/**
 * @param string $guard
 * @return mixed|null
 */
function auth($guard = "users")
{
    switch ($guard) {
        case "admins":
            return isset($_SESSION["userLoggedAdmin"]) ? $_SESSION["userLoggedAdmin"] : null;
        default:
            return isset($_SESSION["userLogged"]) ? $_SESSION["userLogged"] : null;
    }
}

/**
 * @param null $image
 * @return string|null
 */
function image($image = null)
{
    if ($image) {
        return BASE_URL . "assets/images/{$image}";
    }
    return null;
}

/**
 * @param null $image
 * @return string|null
 */
function media($image = null)
{
    if ($image) {
        return BASE_URL . "media/{$image}";
    }
    return null;
}

/**
 * @param null $path
 * @return string|null
 */
function asset($path = null)
{
    if ($path) {
        return BASE_URL . "assets/{$path}";
    }
    return null;
}

/**
 * @param null $path
 * @return string|null
 */
function url($path = null)
{
    if ($path) {
        return BASE_URL . "{$path}";
    }

    return $path;
}

/**
 * @return mixed|string
 */
function back()
{
    return ($_SERVER["HTTP_REFERER"] ?? BASE_URL);
}

/**
 * @param mixed ...$value
 */
function dd(...$value)
{
    print("<pre>".print_r($value,true)."</pre>");
    die;
}

/**
 * @return array
 */
function check_url()
{
    return [
        "admin",
        "admin/home",
        "admin/logout",
        "admin/login",
        "admin/profile",
        "admin/update",
        "admin/users",
    ];
}

/**
 * @param $a
 * @param $b
 * @return float|int
 */
function calc_percent($a, $b)
{
    return (($a / $b) * 100);
}

/**
 * @param $price
 * @return string
 */
function str_price($price)
{
    return number_format(!empty($price) ? $price : 0, 2, ",", ".");
}

/**
 * @param $price
 * @return mixed
 */
function str_price_db($price)
{
    return str_replace([".",","],["","."], !empty($price) ? $price : 0);
}

/**
 * @param $cond
 * @return string
 */
function checked($cond)
{
    return $cond ? 'checked="checked"' : "";
}

/**
 * @param $cond
 * @return string
 */
function selected($cond)
{
    return $cond ? 'selected="selected"' : "";
}

/**
 * @param $id
 * @return mixed
 */
function getError($id)
{
    $errors = [
        1 => "E-mail  e/ou senha inválidos",
        2 => "Não foi possível processar o pagamento, tente novamente.",
    ];

    return $errors[$id];
}

/**
 * @param array $path
 * @return string
 */
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

/**
 * @param $slug
 * @return bool
 */
function hasPermission($slug)
{
    if (in_array($slug, auth("admins")->permissions)) {
        return true;
    }
    return false;
}

/**
 * @param $string
 * @return mixed
 */
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

/**
 * @param string $status
 * @param array $messages
 * @return bool
 */
function setFlashMessage($status = "info", $messages = [])
{
    \Src\Support\Session::set("errors", ["status" => $status, "messages" => $messages]);
    return true;
}

/**
 * @param string $type
 * @return mixed|null
 */
function flashMessage($type = "errors")
{
    /**
     * Example
     * Session::set("errors", ["status" => "danger", "messages" => ["test1","test2","test3"]]);
     */
    if (\Src\Support\Session::has($type)) {
        $flash = \Src\Support\Session::get($type);
        \Src\Support\Session::destroy($type);
        return $flash;
    }
    return null;
}

/**
 * @param $key
 * @param $value
 */
function setInput($key, $value)
{
    \Src\Support\Session::set($key, $value);
}

/**
 * @param null $key
 * @param null $default
 * @return mixed|null
 */
function oldInput($key = null, $default = null)
{
    if (\Src\Support\Session::has($key)) {
        $value = \Src\Support\Session::get($key);
        \Src\Support\Session::destroy($key);
        return $value;
    }
    return $default;
}

/**
 * @param null $key
 */
function clearInput($key = null)
{
    \Src\Support\Session::destroy($key);
}