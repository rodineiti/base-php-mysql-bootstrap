<?php

namespace Src\Controllers;

use Src\Core\Controller;
use Src\Models\User;

class AuthController extends Controller
{
    protected $user;
    protected $data;
    protected $required;

    public function __construct()
    {
        parent::__construct();
        $this->user = new User();
        $this->data = array();
        $this->required = ["name", "email", "password"];
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
            setFlashMessage("danger", ["Método não permitido"]);
            $this->redirect("auth?login");
        }

        $request = filter_var_array($this->request(), FILTER_SANITIZE_STRIPPED);

        $this->required = ["email", "password"];
        if (!$this->required($request)) {
            setFlashMessage("danger", ["Favor, informar seu e-mail e senha"]);
            $this->redirect("auth?login");
        }

        $email = $request["email"];
        $password = $request["password"];

        $user = $this->user->attempt($email, $password);

        if (!$user) {
            setFlashMessage("danger", ["Usuário e/ou Senha errados!"]);
            $this->redirect("auth?login");
        }

        $this->user->setSession($user);

        setFlashMessage("success", ["Bem vindo " . auth()->name]);
        $this->redirect("auth/profile");
    }

    public function save()
    {
        if ($this->method() !== "POST") {
            setFlashMessage("danger", ["Método não permitido"]);
            $this->redirect("auth/register");
        }

        $request = filter_var_array($this->request(), FILTER_SANITIZE_STRIPPED);
        setInput("name", $request["name"] ?? null);
        setInput("email", $request["email"] ?? null);

        if (!$this->required($request)) {
            setFlashMessage("danger", ["Favor, preencher todos os campos"]);
            $this->redirect("auth/register");
        }

        $data["name"] = $request["name"];
        $data["email"] = $request["email"];
        $data["password"] = $request["password"];

        $user = $this->user->create($data);

        if (!$user) {
            $this->redirect("auth/register");
        }

        $this->user->setSession($user);

        clearInput("name"); // clear input
        clearInput("email"); // clear input

        setFlashMessage("success", ["Bem vindo " . auth()->name]);
        $this->redirect("auth/profile");
    }

    public function update()
    {
        if ($this->method() !== "POST") {
            setFlashMessage("danger", ["Método não permitido"]);
            $this->redirect("auth/profile");
        }

        $request = filter_var_array($this->request(), FILTER_SANITIZE_STRIPPED);

        $this->required = ["name"];
        if (!$this->required($request)) {
            setFlashMessage("danger", ["Favor, informar o nome"]);
            $this->redirect("auth/profile");
        }

        $user = $this->user->updateProfile(auth()->id, $request);
        if (!$user) {
            setFlashMessage("danger", ["Favor preencher todos os campos"]);
            $this->redirect("auth/profile");
        } else {
            $this->user->setSession($user);
            setFlashMessage("success", ["Dados atualizados com sucesso"]);
            $this->redirect("auth/profile");
        }
    }

    public function logout()
    {
        session_start();
        $this->user->destroySession();
        header("Location: " . BASE_URL);
    }
}