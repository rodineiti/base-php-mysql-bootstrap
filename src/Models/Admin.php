<?php

namespace Src\Models;

use Src\Core\Model;
use Src\Support\Session;

class Admin extends Model
{
    public function __construct()
    {
        parent::__construct("admins");
    }

    public function all($where = [], $whereIn = [])
    {
        $users = $this->read(true, ["*"], $where, $whereIn) ?? [];
        return $users;
    }

    public function attempt($email, $password)
    {
        $user = $this->read(false, ["*"], ["email" => $email]);

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
        Session::set("userLoggedAdmin", (object)$user);
    }

    public function destroySession()
    {
        Session::destroy("userLoggedAdmin");
    }

    public function create(array $data)
    {
        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if ($this->exists("email", $data["email"])) {
            return false;
        }

        $data["password"] = pwd_gen_hash($data["password"]);

        $user = $this->insert($data);
        return $user;
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
        $newData = array();
        if (!empty($data["email"])) {
            $newData["email"] = $data["email"];
        }

        if (!empty($data["name"])) {
            $newData["name"] = $data["name"];
        }

        if (!empty($data["password"])) {
            $newData["password"] = pwd_gen_hash($data["password"]);
        }

        if (isset($newData["email"]) && !filter_var($newData["email"], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (isset($newData["email"]) && $this->exists("email", $newData["email"], $id)) {
            return false;
        }

        unset($newData["email"]);

        if (count($newData)) {
            if ($this->update($newData, ["id" => $id])) {
                $user = $this->read(false, ["*"], ["id" => $id]);
                return $user;
            }
        }

        return false;
    }

    private function exists($field, $value, $id = null)
    {
        if ($id) {
            return $this->read(false, ["*"], [$field => $value], ["id", "NOT IN", [$id]]);
        }

        return $this->read(false, ["*"], [$field => $value]);
    }
}

?>