<?php

namespace Src\Models;

use Src\Core\Model;
use Src\Support\Session;

class User extends Model
{
    public function __construct()
    {
        parent::__construct("users");
    }

    public function attempt($email, $password)
    {
        $user = $this->select()->where("email", "=", $email)->get();

        if (!$user) {
            return false;
        }

        if (!pwd_verify($password, $user->password)) {
            return false;
        }

        if (pwd_rehash($user->password)) {
            $this->update(["password" => pwd_gen_hash($password)],  ["id" => $user->id]);
        }

        return $user;
    }

    public function setSession($user)
    {
        Session::set("userLogged", (object)$user);
    }

    public function destroySession()
    {
        Session::destroy("userLogged");
    }

    public function create(array $data)
    {
        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            setFlashMessage("danger", ["Favor, informar um e-mail válido"]);
            return false;
        }

        if ($this->exists("email", $data["email"])) {
            setFlashMessage("danger", ["Este e-mail já está em uso, favor verificar"]);
            return false;
        }

        $data["password"] = pwd_gen_hash($data["password"]);

        $userId = $this->insert($data);

        if ($userId) {
            return $this->getById($userId);
        }

        return null;
    }

    public function getById($id, $columns = ["*"])
    {
        $user = $this->findById($id, $columns);

        if ($user) {
            return $user;
        }
        return null;
    }

    public function updateProfile($id, array $data)
    {
        if (!empty($data["password"])) {
            $data["password"] = pwd_gen_hash($data["password"]);
        }

        if (isset($data["email"]) && !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            setFlashMessage("danger", ["Favor, informar um e-mail válido"]);
            return false;
        }

        if (isset($data["email"]) && $this->exists("email", $data["email"], $id)) {
            setFlashMessage("danger", ["Este e-mail já está em uso, favor verificar"]);
            return false;
        }

        unset($data["email"]);

        if (count($data)) {
            if ($this->update($data, ["id" => $id])) {
                $user = $this->getById($id);
                return $user;
            }
        }

        return false;
    }

    public function exists($field, $value, $id = null)
    {
        if ($id) {
            return $this->select()->where($field, "=", $value)->whereNotIn("id", [$id])->get();
        }

        return $this->select()->where($field, "=", $value)->get();
    }

    public function destroy($id)
    {
        if ($id === auth()->id) {
            return false;
        }

        return $this->delete(["id" => $id]);
    }
}

?>