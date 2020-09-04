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
        switch ($this->method())
        {
            case "GET":
                return $_GET;
                break;
            case "POST":
                $data = json_decode(file_get_contents("php://input")) ?? $_POST;
                return (array) $data;
                break;
            case "PUT":
            case "DELETE":
                parse_str(file_get_contents("php://input"), $data);
                return (array) $data;
                break;
            default:
                return false;
        }
    }

    /**
     * @param null $path
     */
    public function redirect($path = null)
    {
        if ($path) {
            header("Location: " . BASE_URL . $path);
            exit;
        }

        header("Location: " . BASE_URL);
        exit;
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
                if (!isset($_SESSION['userLoggedAdmin']) || empty($_SESSION['userLoggedAdmin'])) {
                    header("Location: " . BASE_URL . "admin?login");
                    exit;
                }
                break;
            default:
                if (!isset($_SESSION['userLogged']) || empty($_SESSION['userLogged'])) {
                    header("Location: " . BASE_URL . "auth?login");
                    exit;
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
        foreach ($this->required as $field) {
            if (empty($request[$field])) {
                return false;
            }
        }
        return true;
    }
}