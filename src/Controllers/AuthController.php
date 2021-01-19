<?php

namespace Src\Controllers;

use Src\Core\Controller;
use Src\Models\User;
use Src\Support\Auth;

class AuthController extends Controller
{
    protected $data;
    protected $required;

    public function __construct()
    {
        parent::__construct();
        $this->data = array();
        $this->required = ["name", "email", "password"];
    }

    public function index()
    {
        if (check()) {
            back_route(route("profile"));
        }

        $this->template("login", $this->data);
    }

    public function register()
    {
        if (check()) {
            back_route(route("profile"));
        }

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
            back_route();
        }

        $email = $request["email"];
        $password = $request["password"];

        $user = (new User())->attempt($email, $password);

        if (!$user) {
            setFlashMessage("danger", ["Usuário e/ou Senha errados!"]);
            back_route();
        }

        Auth::setSession("user", $user->id);

        setFlashMessage("success", ["Bem vindo " . auth()->name]);
        back_route(route("profile"));
    }

    public function save()
    {
        $data = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);
        setInput("name", $data["name"] ?? null);
        setInput("email", $data["email"] ?? null);

        if (!$this->required($data)) {
            setFlashMessage("danger", ["Favor, preencher todos os campos"]);
            back_route();
        }

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];

        if (!$user->save()) {
            setFlashMessage("danger", [$user->error()->getMessage()]);
            back_route();
        }

        Auth::setSession("user", $user->id);

        clearInput("name"); // clear input
        clearInput("email"); // clear input

        setFlashMessage("success", ["Bem vindo " . auth()->name]);
        back_route(route("profile"));
    }

    public function update()
    {
        $data = filter_var_array($this->request()->all(), FILTER_SANITIZE_STRIPPED);

        $this->required = ["name"];
        if (!$this->required($data)) {
            setFlashMessage("danger", ["Favor, informar o nome"]);
            back_route();
        }

        if ($this->request()->hasFile('avatar')) {
            $avatar = $this->request()->file('avatar');
            if (!$avatar["error"]) {
                $data["avatar"] = $avatar;
            }
        }

        $user = auth();
        $user->name = $data['name'];
        $user->password = (!empty($data["password"]) ? $data["password"] : $user->password);

        if (isset($data["avatar"]) && count($data["avatar"])) {
            if (in_array($data["avatar"]["type"], ["image/jpeg", "image/jpg", "image/png"])) {
                $data["avatar"] = cutImage(
                    $data["avatar"],
                    200,
                    200,
                    CONF_UPLOAD_FILE_AVATARS
                );
                removeFile(CONF_UPLOAD_FILE_AVATARS, $user->avatar);

                $user->avatar = (!empty($data["avatar"]) ? $data["avatar"] : $user->avatar);
            }
        }

        if (!$user->save()) {
            setFlashMessage("danger", [$user->error()->getMessage()]);
        }

        setFlashMessage("success", ["Perfil atualizado com sucesso"]);
        back_route();
    }

    public function logout()
    {
        Auth::destroySession("user");
        back_route(route("home"));
    }
}