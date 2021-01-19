<?php

namespace Src\Controllers\Admin;

use Src\Core\Controller;
use Src\Models\User;
use Src\Support\Auth;

class UsersController extends Controller
{
    protected $data;
    protected $required;

    public function __construct()
    {
        parent::__construct("admin/template");
        $this->auth("admins");

        $this->data = array();
        $this->required = ["name", "email", "password"];
    }

    public function index()
    {
        $this->data["users"] = (new User())->select()->all();
        $this->template("admin/users/index", $this->data);
    }

    public function create()
    {
        $this->template("admin/users/create");
    }

    public function store()
    {
        $data = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);

        if (!$this->required($data)) {
            setFlashMessage("danger", ["Favor, preencher todos os campos"]);
            back_route(route("admin.users.create"));
        }

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];

        if (!$user->save()) {
            setFlashMessage("danger", [$user->error()->getMessage()]);
            back_route(route("admin.users.create"));
        }

        Auth::setSession("user", $user->id);

        clearInput("name"); // clear input
        clearInput("email"); // clear input

        setFlashMessage("success", ["Bem vindo " . auth()->name]);
        back_route(route("profile"));
    }

    public function edit($id)
    {
        if (!$user = (new User())->findById($id)) {
            setFlashMessage("danger", ["Usuário não encontrado."]);
            back_route(route("admin.users.index"));
        }

        $this->data = array();
        $this->data["user"] = $user;
        $this->template("admin/users/edit", $this->data);
    }

    public function update($id)
    {
        $data = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);

        if (!$user = (new User())->findById($id)) {
            setFlashMessage("danger", ["Usuário não encontrado."]);
            back_route(route("admin.users.index"));
        }

        $this->required = ["name", "email"];
        if (!$this->required($data)) {
            setFlashMessage("danger", ["Favor, preencher todos os campos"]);
            back_route(route("admin.users.edit", ["id" => $id]));
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = (!empty($data["password"]) ? $data["password"] : $user->password);

        if (!$user->save()) {
            setFlashMessage("danger", [$user->error()->getMessage()]);
            back_route(route("admin.users.edit", ["id" => $id]));
        }

        back_route(route("admin.users.index"));
    }

    public function destroy($id)
    {
        if (!$user = (new User())->findById($id)) {
            setFlashMessage("danger", ["Usuário não encontrado."]);
            back_route(route("admin.users.index"));
        }

        $user->destroy();

        setFlashMessage("success", ["Usuário deletado com sucesso"]);
        back_route(route("admin.users.index"));
    }
}