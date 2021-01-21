<?php

namespace Src\Controllers\Admin;

use Src\Core\Controller;
use Src\Models\User;

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
        $data = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);

        $limit = $this->limit ?? 10;
        $page = !empty($data["page"]) ? intval($data["page"]) : 1;
        $offset = (($page * $limit) - $limit);

        $total = (new User())->select()->count();
        $pages = ceil($total / $limit);

        $this->data["list"] = (new User())->select()->limit($limit)->offset($offset)->all() ?? [];
        $this->data["page"] = $page;
        $this->data["pages"] = $pages;
        $this->data["total"] = $total;
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

        $model = new User();
        $model->name = $data['name'];
        $model->email = $data['email'];
        $model->password = $data['password'];

        if (!$model->save()) {
            setFlashMessage("danger", [$model->error()->getMessage()]);
            back_route(route("admin.users.create"));
        }

        setFlashMessage("success", ["Usuário adicionado com sucesso"]);
        back_route(route("admin.users.index"));
    }

    public function edit($id)
    {
        if (!$model = (new User())->findById($id)) {
            setFlashMessage("danger", ["Usuário não encontrado."]);
            back_route(route("admin.users.index"));
        }

        $this->data["item"] = $model;
        $this->template("admin/users/edit", $this->data);
    }

    public function update($id)
    {
        $data = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);

        if (!$model = (new User())->findById($id)) {
            setFlashMessage("danger", ["Usuário não encontrado."]);
            back_route(route("admin.users.index"));
        }

        $this->required = ["name", "email"];
        if (!$this->required($data)) {
            setFlashMessage("danger", ["Favor, preencher todos os campos"]);
            back_route(route("admin.users.edit", ["id" => $id]));
        }

        $model->name = $data['name'];
        $model->email = $data['email'];
        $model->password = (!empty($data["password"]) ? $data["password"] : $model->password);

        if (!$model->save()) {
            setFlashMessage("danger", [$model->error()->getMessage()]);
            back_route(route("admin.users.edit", ["id" => $id]));
        }

        setFlashMessage("success", ["Usuário atualizado com sucesso"]);
        back_route(route("admin.users.index"));
    }

    public function destroy($id)
    {
        if (!$model = (new User())->findById($id)) {
            setFlashMessage("danger", ["Usuário não encontrado."]);
            back_route(route("admin.users.index"));
        }

        removeFile(CONF_UPLOAD_FILE_AVATARS, $model->avatar);

        $model->destroy();

        setFlashMessage("success", ["Usuário deletado com sucesso"]);
        back_route(route("admin.users.index"));
    }
}