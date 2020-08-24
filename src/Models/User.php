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

    public function all($filters = [], $limit = null, $offset = null)
    {
        $query = "SELECT * FROM users ";

        $where = $this->buildWhere($filters);
        $query .= " WHERE " . implode(" AND ", $where);

        if ($limit) {
            $query .= " LIMIT {$limit} ";
        }

        if ($offset) {
            $query .= " OFFSET {$offset} ";
        }

        $results = $this->customQuery($query) ?? [];

        return $results;
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
        Session::set("userLogged", (object)$user);
    }

    public function destroySession()
    {
        Session::destroy("userLogged");
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
            return false;
        }

        if (isset($data["email"]) && $this->exists("email", $data["email"], $id)) {
            return false;
        }

        unset($data["email"]);

        if (count($data)) {
            if ($this->update($data, ["id" => $id])) {
                $user = $this->read(false, ["*"], ["id" => $id]);
                return $user;
            }
        }

        return false;
    }

    public function exists($field, $value, $id = null)
    {
        if ($id) {
            return $this->read(false, ["*"], [$field => $value], ["id", "NOT IN", [$id]]);
        }

        return $this->read(false, ["*"], [$field => $value]);
    }

    public function destroy($id)
    {
        return $this->delete(["id" => $id]);
    }

    private function buildWhere($filters = [])
    {
        $where = [];

        if (!empty($filters["searchTerm"])) {
            $where[] = "(users.name LIKE '%" . $filters["searchTerm"] . "%' OR users.email LIKE '%" . $filters["searchTerm"] . "%') ";
        }

        return $where;
    }
}

?>