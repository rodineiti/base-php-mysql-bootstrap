<?php

namespace Src\Core;

use Src\Support\Jwt;

class Controller
{
    /**
     * @var string
     */
    protected $lang;
    /**
     * @var int
     */
    protected $limit = 10;
    /**
     * @var string
     */
    protected $theme;

    /**
     * @var array
     */
    protected $required;

    /**
     * Controller constructor.
     * @param string $theme
     */
    public function __construct($theme = "template")
    {
        $this->theme = $theme;
    }

    /**
     * @return mixed
     */
    public function method()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     * @return array|bool
     */
    public function request()
    {
        return request();
    }

    /**
     * @param null $path
     */
    public function redirect($path = null)
    {
        return redirect($path);
    }

    /**
     * @param array $data
     */
    public function json($data = [])
    {
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @param $user
     * @return string
     */
    protected function generateJwt($user)
    {
        return Jwt::generate(["user_id" => $user]);
    }

    /**
     * @param $token
     * @return bool
     */
    protected function verifyJwt($token)
    {
        $jwtValidate = Jwt::validate($token);
        return (isset($jwtValidate->user_id)) ? $jwtValidate->user_id : false;
    }

    /**
     * @param string $view
     * @param array $data
     */
    public function view(string $view, array $data = [])
    {
        /**
         * extract
         * transforms an array into variables, you can directly access the key name in the array
         * example: $data = ["key" => "value"]
         * take it this way: $key, only that it will already print "value"
         */

        extract($data);
        include __DIR__ . "/../../views/{$view}.php";
    }

    /**
     * @param string $view
     * @param array $data
     */
    public function template(string $view, array $data = [])
    {
        include __DIR__ . "/../../views/{$this->theme}.php";
    }

    /**
     * @param string $view
     * @param array $data
     */
    public function viewTemplate(string $view, array $data = [])
    {
        /**
         * extract
         * transforms an array into variables, you can directly access the key name in the array
         * example: $data = ["key" => "value"]
         * take it this way: $key, only that it will already print "value"
         */
        extract($data);
        include __DIR__ . "/../../views/{$view}.php";
    }

    /**
     * @param string $guard
     */
    protected function auth($guard = "users")
    {
        switch ($guard) {
            case "admins":
                if (!check($guard)) {
                    back_route(route("admin.login"));
                }
                break;
            case "recruiters":
                if (!check($guard)) {
                    back_route(route("recruiter.login"));
                }
                break;
            default:
                if (!check($guard)) {
                    back_route(route("login"));
                }
                break;
        }
    }

    /**
     * @param $request
     * @return bool
     */
    protected function required($request)
    {
        $messages = [];
        foreach ($this->required as $field) {
            if (empty($request[$field])) {
                $messages[] = "Favor preencher o campo: " . field_name($field);
            }
        }

        if (count($messages)) {
            setFlashMessage("danger", $messages);
            return false;
        }

        return true;
    }
}