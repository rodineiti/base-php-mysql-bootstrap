<?php

namespace Src\Controllers\Admin;

use Src\Core\Controller;
use Src\Models\Admin as User;

class AdminController extends Controller
{
    protected $user;
    protected $data;

    public function __construct()
    {
        parent::__construct("admin/template");
        $this->user = new User();
        $this->data = array();
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
        if(isset($_POST["email"]) && !empty($_POST["email"])) {
            $email = addslashes($_POST["email"]);
            $password = $_POST["password"];

            $user = $this->user->attempt($email, $password);

            if (!$user) {
                header("Location: " . BASE_URL . "admin?login&error=true");
                exit;
            }

            $this->user->setSession($user);

            setFlashMessage("success", ["Bem vindo " . auth("admins")->name]);
            header("Location: " . BASE_URL . "admin/home");
            exit;
        }

        header("Location: " . BASE_URL . "admin?login&error=true");
        exit;
    }

    public function update()
    {
        if($_POST) {
            if (!$this->user->updateProfile(auth("admins")->id, $_POST)) {
                header("Location: " . BASE_URL . "admin/profile?error=fields");
                exit;
            } else {
                header("Location: " . BASE_URL . "admin/profile?success=true");
                exit;
            }
        }

        header("Location: " . BASE_URL . "admin/profile?error=fields");
        exit;
    }

    public function logout()
    {
        session_start();
        $this->user->destroySession();
        header("Location: " . BASE_URL . "admin?login");
    }
}