<?php

namespace Src\Controllers\Admin;

use Src\Core\Controller;
use Src\Models\Admin as User;
use Src\Support\Auth;

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
        $this->template("admin/login");
    }

    public function profile()
    {
        $this->template("admin/profile");
    }

    public function login()
    {
        $request = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);

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

        Auth::setSession("admin", $user->id);

        setFlashMessage("success", ["Bem vindo " . auth("admins")->name]);
        return back_route(route("admin.home"));
    }

    public function update()
    {
        $request = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);

        $this->required = ["name"];
        if (!$this->required($request)) {
            setFlashMessage("danger", ["Favor, informar o nome"]);
            return back_route();
        }

        $user = $this->user->updateProfile(auth("admins")->id, $request);
        if (!$user) {
            setFlashMessage("danger", ["Favor preencher todos os campos"]);
            return back_route();
        } else {
            Auth::setSession("admin", $user->id);
            setFlashMessage("success", ["Dados atualizados com sucesso"]);
            return back_route();
        }
    }

    public function logout()
    {
        Auth::destroySession("admin");
        return back_route(route("admin.login"));
    }
}