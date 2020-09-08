<?php

namespace Src\Models;

use Src\Core\Model;
use Src\Support\Session;

/**
 * Class User
 * @package Src\Models
 */
class User extends Model
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("users");
    }

    /**
     * @param $email
     * @param $password
     * @return array|bool|mixed|null
     */
    public function attempt($email, $password)
    {
        $user = $this->select()->where("email", "=", $email)->first();

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

    /**
     * @param $user
     */
    public function setSession($user)
    {
        Session::set("userLogged", (object)$user);
    }

    /**
     *
     */
    public function destroySession()
    {
        Session::destroy("userLogged");
    }

    /**
     * @param array $data
     * @return array|bool|mixed|null
     */
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

    /**
     * @param $id
     * @param array $columns
     * @return array|mixed|null
     */
    public function getById($id, $columns = ["*"])
    {
        $user = $this->findById($id, $columns);

        if ($user) {
            return $user;
        }
        return null;
    }

    /**
     * @param $id
     * @param array $data
     * @return array|bool|mixed|null
     */
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

    /**
     * @param $field
     * @param $value
     * @param null $id
     * @return array|mixed|null
     */
    public function exists($field, $value, $id = null)
    {
        if ($id) {
            return $this->select()->where($field, "=", $value)->whereNotIn("id", [$id])->first();
        }

        return $this->select()->where($field, "=", $value)->first();
    }

    /**
     * @param $id
     * @return bool
     */
    public function destroy($id)
    {
        if ($id === auth()->id) {
            return false;
        }

        return $this->delete(["id" => $id]);
    }
}

?>