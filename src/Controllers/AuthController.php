<?php

namespace Src\Controllers;

use Src\Core\Controller;
use Src\Models\User;

class AuthController extends Controller
{
    protected $user;
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
        $this->data = array();
    }

    public function index()
    {
        $this->template("login", $this->data);
    }

    public function register()
    {
        $this->template("register", $this->data);
    }

    public function profile()
    {
        $this->template("profile", $this->data);
    }

    public function login()
    {
        if ($this->method() !== "POST") {
            header("Location: " . BASE_URL . "auth?login&error=true");
            exit;
        }

        $request = filter_var_array($this->request(), FILTER_SANITIZE_STRIPPED);

        if(isset($request["email"]) && !empty($request["email"])) {
            $email = $request["email"];
            $password = $request["password"];

            $user = $this->user->attempt($email, $password);

            if (!$user) {
                header("Location: " . BASE_URL . "auth?login&error=true");
                exit;
            }

            $this->user->setSession($user);

            setFlashMessage("success", ["Bem vindo " . auth()->name]);
            header("Location: " . BASE_URL . "auth/profile");
            exit;
        }

        header("Location: " . BASE_URL . "auth?login&error=true");
        exit;
    }

    public function save()
    {
        if ($this->method() !== "POST") {
            header("Location: " . BASE_URL . "auth/register?error=fields");
            exit;
        }

        $request = filter_var_array($this->request(), FILTER_SANITIZE_STRIPPED);

        if(isset($request["name"]) && !empty($request["name"])) {
            $data["name"] = $request["name"];
            $data["email"] = $request["email"];
            $data["password"] = $request["password"];

            if (empty($data["name"]) || empty($data["email"]) || empty($data["password"])) {
                header("Location: " . BASE_URL . "auth/register?error=fields");
                exit;
            }

            $user = $this->user->create($data);

            if (!$user) {
                header("Location: " . BASE_URL . "auth/register?error=exists");
                exit;
            }

            header("Location: " . BASE_URL . "auth/register?success=true");
            exit;
        }

        header("Location: " . BASE_URL . "auth/register?error=fields");
        exit;
    }

    public function update()
    {
        if ($this->method() !== "POST") {
            header("Location: " . BASE_URL . "auth/profile?error=fields");
            exit;
        }

        $request = filter_var_array($this->request(), FILTER_SANITIZE_STRIPPED);

        if($request) {
            $user = $this->user->updateProfile(auth()->id, $request);
            if (!$user) {
                header("Location: " . BASE_URL . "auth/profile?error=fields");
                exit;
            } else {
                $this->user->setSession($user);
                header("Location: " . BASE_URL . "auth/profile?success=true");
                exit;
            }
        }

        header("Location: " . BASE_URL . "auth/profile?error=fields");
        exit;
    }

    public function logout()
    {
        session_start();
        $this->user->destroySession();
        header("Location: " . BASE_URL);
    }
}