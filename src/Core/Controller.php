<?php

namespace Src\Core;

use Src\Support\Jwt;

class Controller
{
    protected $lang;
    protected $limit = 10;
    protected $theme;

    public function __construct($theme = "template")
    {
        $this->theme = $theme;
    }

    public function method()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

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

    public function json($data = [])
    {
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function generateJwt($user)
    {
        return Jwt::generate(["user_id" => $user]);
    }

    protected function verifyJwt($token)
    {
        $jwtValidate = Jwt::validate($token);
        return (isset($jwtValidate->user_id)) ? $jwtValidate->user_id : false;
    }

    public function view(string $view, array $data = [])
    {
        /**
         * extract
         * transforma um array em variáveis, pode acessar diretamente o nome da chave no array
         * exemplo: $data = ["chave" => "valor]
         * pega desta forma: $chave, somente que já irá imprimir "valor"
         */

        extract($data);
        include "views/{$view}.php";
    }

    public function template(string $view, array $data = [])
    {
        include "views/{$this->theme}.php";
    }

    public function viewTemplate(string $view, array $data = [])
    {
        /**
         * extract
         * transforma um array em variáveis, pode acessar diretamente o nome da chave no array
         * exemplo: $data = ["chave" => "valor]
         * pega desta forma: $chave, somente que já irá imprimir "valor"
         */
        extract($data);
        include "views/{$view}.php";
    }

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