<?php

namespace Src\Controllers\Admin;

use Src\Core\Controller;
use Src\Models\Admin as User;
use Src\Support\Auth;

class AdminController extends Controller
{
    protected $data;
    protected $required;

    public function __construct()
    {
        parent::__construct("admin/template");
        $this->data = array();
        $this->required = ["email", "password"];
    }

    public function index()
    {
        if (check("admins")) {
            back_route(route("admin.home"));
        }

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
            back_route();
        }

        $email = $request["email"];
        $password = $request["password"];

        $user = (new User())->attempt($email, $password);

        if (!$user) {
            setFlashMessage("danger", ["Usuário e/ou Senha errados!"]);
            back_route();
        }

        Auth::setSession("admin", $user->id);

        setFlashMessage("success", ["Bem vindo " . auth("admins")->name]);
        back_route(route("admin.home"));
    }

    public function update()
    {
        $data = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);

        $this->required = ["name"];
        if (!$this->required($data)) {
            setFlashMessage("danger", ["Favor, informar o nome"]);
            back_route();
        }

        $user = auth("admins");
        $user->name = $data['name'];
        $user->password = (!empty($data["password"]) ? $data["password"] : $user->password);

        if (!$user->save()) {
            setFlashMessage("danger", [$user->error()->getMessage()]);
        }

        setFlashMessage("success", ["Perfil atualizado com sucesso"]);
        back_route();
    }

    public function logout()
    {
        Auth::destroySession("admin");
        back_route(route("admin.login"));
    }
}