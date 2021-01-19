<?php

namespace Src\Controllers;

use Src\Core\Controller;
use Src\Models\User;
use Src\Support\Auth;

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
        $request = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);

        $this->required = ["email", "password"];
        if (!$this->required($request)) {
            setFlashMessage("danger", ["Favor, informar seu e-mail e senha"]);
            return back_route();
        }

        $email = $request["email"];
        $password = $request["password"];

        $user = $this->user->attempt($email, $password);

        if (!$user) {
            setFlashMessage("danger", ["Usuário e/ou Senha errados!"]);
            return back_route();
        }

        Auth::setSession("user", $user->id);

        setFlashMessage("success", ["Bem vindo " . auth()->name]);
        return back_route(route("profile"));
    }

    public function save()
    {
        $request = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);
        setInput("name", $request["name"] ?? null);
        setInput("email", $request["email"] ?? null);

        if (!$this->required($request)) {
            setFlashMessage("danger", ["Favor, preencher todos os campos"]);
            return back_route();
        }

        $data["name"] = $request["name"];
        $data["email"] = $request["email"];
        $data["password"] = $request["password"];

        $user = $this->user->create($data);

        if (!$user) {
            return back_route();
        }

        Auth::setSession("user", $user->id);

        clearInput("name"); // clear input
        clearInput("email"); // clear input

        setFlashMessage("success", ["Bem vindo " . auth()->name]);
        return back_route(route("profile"));
    }

    public function update()
    {
        $request = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);

        if ($this->request()->hasFile('avatar')) {
            $avatar = $this->request()->file('avatar');
            if (!$avatar["error"]) {
                $request["avatar"] = $avatar;
            }
        }

        $this->required = ["name"];
        if (!$this->required($request)) {
            setFlashMessage("danger", ["Favor, informar o nome"]);
            return back_route();
        }

        $user = $this->user->updateProfile(auth(), $request);
        if (!$user) {
            setFlashMessage("danger", ["Favor preencher todos os campos"]);
            return back_route(route("profile"));
        } else {
            Auth::setSession("user", $user->id);
            setFlashMessage("success", ["Dados atualizados com sucesso"]);
            return back_route(route("profile"));
        }
    }

    public function logout()
    {
        Auth::destroySession("user");
        return back_route(route("home"));
    }
}