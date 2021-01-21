<?php

use Src\Router\Route;
use Src\Router\Request;

/**
 * @return Request
 */
function request() {
    return new Request;
}

/**
 * @param null $request
 * @return mixed|void
 */
function resolve($request = null) {
    if (is_null($request)) {
        $request = request();
    }
    return Route::resolve($request);
}

/**
 * @param $name
 * @param null $params
 * @return bool|string
 */
function route($name, $params = null) {
    return Route::translate($name, $params);
}

/**
 * @param $pattern
 * @return mixed|void
 */
function redirect($pattern) {
    return resolve($pattern);
}

/**
 * @param null $path
 */
function back_route($path = null) {
    if ($path) {
        header('Location: ' . $path);
        exit;
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

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
            return \Src\Support\Auth::admin();
         case "recruiters":
             return \Src\Support\Auth::recruiter();
        default:
            return \Src\Support\Auth::user();
    }
}

/**
 * @param string $guard
 * @return boolean
 */
function check($guard = "users")
{
    switch ($guard) {
        case "admins":
            if (\Src\Support\Session::has("admin")) {
                return true;
            }
            break;
        case "recruiters":
            if (\Src\Support\Session::has("recruiter")) {
                return true;
            }
            break;
        default:
            if (\Src\Support\Session::has("user")) {
                return true;
            }
    }

    return false;
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
    $url = isset($_GET["uri"]) ? $_GET["uri"] : "home";

    if (count(explode("/", $_GET["uri"])) >= 4) {
        $arr = explode("/", $_GET["uri"]);
        $url = "{$arr[0]}/{$arr[1]}/{$arr[2]}";
    }

    if (count($path) && in_array($url, $path)) {
        return "active";
    }
    return "";
}

/**
 * @param $string
 * @return mixed
 */
function str_slug($string)
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª|';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

    $slug = str_replace(["-----","----", "---", "--"], "-",
        str_replace(" ", "-",
            trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
        )
    );
    return $slug;
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars($string, $limit, $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    return "{$chars}{$pointer}";
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

/**
 * @param $file
 * @param $width
 * @param $height
 * @param $folder
 * @return string
 */
function cutImage($file, $width = 0, $height = 0, $folder)
{
    list($wOriginal, $hOriginal) = getimagesize($file["tmp_name"]);
    $rate = ($wOriginal / $hOriginal);

    $width = ($width <= 0) ? $wOriginal : $width;
    $height = ($height <= 0) ? $hOriginal : $height;

    $newWidth = $width;
    $newHeight = ($newWidth / $rate);

    if ($newHeight < $height) {
        $newHeight = $height;
        $newWidth = ($newHeight * $rate);
    }

    $x = ($width - $newWidth);
    $y = ($height - $newHeight);
    $x = $x < 0 ? $x / 2 : $x;
    $y = $y < 0 ? $y / 2 : $y;

    $imageFinal = imagecreatetruecolor($width, $height);
    switch ($file["type"]) {
        case "image/jpeg":
        case "image/jpg":
            $image = imagecreatefromjpeg($file["tmp_name"]);
            break;
        case "image/png":
            $image = imagecreatefrompng($file["tmp_name"]);
    }

    imagecopyresampled($imageFinal, $image, $x, $y, 0,0, $newWidth, $newHeight, $wOriginal, $hOriginal);

    $filename = md5(time().rand(0,9999)).".jpg";
    imagejpeg($imageFinal, $folder."/".$filename);

    return $filename;
}

/**
 * @param $folder
 * @param $file
 */
function removeFile($folder, $file)
{
    if (!empty($file))
    {
        $filePath = $folder."/".$file;

        if (file_exists($filePath))
        {
            @unlink($filePath);
        }
    }
}

/**
 * @param string $path
 * @return string
 */
function url($path = null)
{
    if (strstr($_SERVER["HTTP_HOST"], "localhost")) {
        if ($path) {
            return BASE_URL_TEST . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return BASE_URL_TEST;
    }

    if ($path) {
        return BASE_URL . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return BASE_URL;
}

/**
 * @param $index
 * @param bool $lower
 * @return mixed|string
 */
function type_job($index, $lower = false)
{
    $arr = [
        'Freelance',
        'Part Time',
        'Full Time',
        'Internship'
    ];

    $value = str_replace(" ", "-", $arr[$index]);

    return $lower ? strtolower($value) : $value;
}

/**
 * @param array $files
 * @param $page
 */
function loadJSAdmin($files = [], $pages = [])
{
    $url = isset($_GET["uri"]) ? $_GET["uri"] : "";

    if (count(explode("/", $_GET["uri"])) >= 4) {
        $arr = explode("/", $_GET["uri"]);
        $url = "{$arr[0]}/{$arr[1]}/{$arr[2]}";
    }

    if (in_array($url, $pages)) {
        foreach ($files as $key => $value) {
            echo '<script src="'.asset($value).'"></script>';
        }
    }
}

/**
 * @param array $files
 * @param $page
 */
function loadCSSAdmin($files = [], $pages = [])
{
    $url = isset($_GET["uri"]) ? $_GET["uri"] : "";

    if (count(explode("/", $_GET["uri"])) >= 4) {
        $arr = explode("/", $_GET["uri"]);
        $url = "{$arr[0]}/{$arr[1]}/{$arr[2]}";
    }

    if (in_array($url, $pages)) {
        foreach ($files as $key => $value) {
            echo '<link rel="stylesheet" href="'.asset($value).'" />';
        }
    }
}

/**
 * @param $index
 * @param bool $lower
 * @return mixed|string
 */
function field_name($field)
{
    $arr = [
        "type_id" => "Tipo da vaga",
        "level_id" => "Nível da vaga",
        "area_id" => "Área",
        "position" => "Cargo",
        "company" => "Empresa",
        "city" => "Cidade",
        "state" => "Estado",
        "country" => "País",
        "location" => "Localização",
        "remote" => "Vaga remota?",
        "vacancies" => "Total de vagas",
        "description" => "Descrição",
        "salary_range_initial" => "Faixa Salarial Inicial",
        "salary_range_final" => "Faixa Salarial Final",
        "salary_period" => "Faixa Salarial Período",
        "contact_email" => "E-mail de contato",
        "company_logo" => "Logo da empresa",
    ];

    return $arr[$field] ?? $field;
}


