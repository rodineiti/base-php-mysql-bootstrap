<?php

namespace Src\Controllers\Admin;

use Src\Core\Controller;
use Src\Models\Admin as User;

class AdminController extends Controller
{
    protected $user;
    protected $data;
    protected $required;

    public function __construct()
    {
        parent::__construct("admin/template");
        $this->user = new User();
        $this->data = array();
        $this->required = ["email", "password"];
    }

    public function index()
    {
        if (auth("admins")) {
            $this->home();
        } else {
            $this->template("admin/login");
        }
    }

    public function home()
    {
        $this->auth("admins");

        $this->template("admin/home", $this->data);
    }

    public function profile()
    {
        $this->template("admin/profile");
    }

    public function login()
    {
        if ($this->method() !== "POST") {
            setFlashMessage("danger", ["Método não permitido"]);
            $this->redirect("admin?login");
        }

        $request = filter_var_array($this->request(), FILTER_SANITIZE_STRIPPED);

        if (!$this->required($request)) {
            setFlashMessage("danger", ["Favor, informar seu e-mail e senha"]);
            $this->redirect("admin?login");
        }

        $email = $request["email"];
        $password = $request["password"];

        $user = $this->user->attempt($email, $password);

        if (!$user) {
            setFlashMessage("danger", ["Usuário e/ou Senha errados!"]);
            $this->redirect("admin?login");
        }

        $this->user->setSession($user);

        setFlashMessage("success", ["Bem vindo " . auth("admins")->name]);
        $this->redirect("admin/home");
    }

    public function update()
    {
        if ($this->method() !== "POST") {
            setFlashMessage("danger", ["Método não permitido"]);
            $this->redirect("admin/profile");
        }

        $request = filter_var_array($this->request(), FILTER_SANITIZE_STRIPPED);

        $this->required = ["name"];
        if (!$this->required($request)) {
            setFlashMessage("danger", ["Favor, informar o nome"]);
            $this->redirect("admin/profile");
        }

        $user = $this->user->updateProfile(auth("admins")->id, $request);
        if (!$user) {
            setFlashMessage("danger", ["Favor preencher todos os campos"]);
            $this->redirect("admin/profile");
        } else {
            $this->user->setSession($user);
            setFlashMessage("success", ["Dados atualizados com sucesso"]);
            $this->redirect("admin/profile");
        }
    }

    public function logout()
    {
        session_start();
        $this->user->destroySession();
        header("Location: " . BASE_URL . "admin?login");
    }
}