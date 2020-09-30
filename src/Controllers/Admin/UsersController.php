<?php

namespace Src\Controllers\Admin;

use Src\Core\Controller;
use Src\Models\User;

class UsersController extends Controller
{
    protected $user;
    protected $data;
    protected $required;

    public function __construct()
    {
        parent::__construct("admin/template");
        $this->auth("admins");

        $this->user = new User();
        $this->data = array();
        $this->required = ["name", "email", "password"];
    }

    public function index()
    {
        $this->data["users"] = $this->user->select()->all();
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
            return back_route(route("admin.users.create"));
        }

        $user = $this->user->create($data);

        if (!$user) {
            return back_route(route("admin.users.create"));
        }

        setFlashMessage("success", ["Usuário adicionado com sucesso"]);
        return back_route(route("admin.users.index"));
    }

    public function edit($id)
    {
        if (!$user = $this->user->getById($id)) {
            setFlashMessage("danger", ["Usuário não encontrado."]);
            return back_route(route("admin.users.index"));
        }

        $this->data = array();
        $this->data["user"] = $user;
        $this->template("admin/users/edit", $this->data);
    }

    public function update($id)
    {
        $data = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);

        if (!$this->user->getById($id)) {
            setFlashMessage("danger", ["Usuário não encontrado."]);
            return back_route(route("admin.users.index"));
        }

        $this->required = ["name", "email"];
        if (!$this->required($data)) {
            setFlashMessage("danger", ["Favor, preencher todos os campos"]);
            return back_route(route("admin.users.edit", ["id" => $id]));
        }

        if ($this->user->updateProfile($id, $data)) {
            return back_route(route("admin.users.edit", ["id" => $id]));
        }

        setFlashMessage("success", ["Usuário atualizado com sucesso"]);
        return back_route(route("admin.users.index"));
    }

    public function destroy($id)
    {
        if (!$user = $this->user->getById($id)) {
            setFlashMessage("danger", ["Usuário não encontrado."]);
            return back_route(route("admin.users.index"));
        }

        $this->user->destroy($user->id);

        setFlashMessage("success", ["Usuário deletado com sucesso"]);
        return back_route(route("admin.users.index"));
    }
}