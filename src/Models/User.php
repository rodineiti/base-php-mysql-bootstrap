<?php

namespace Src\Models;

use Src\Core\Model;
use Exception;

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
            $user->password = pwd_gen_hash($password);
            $user->save();
        }

        return $user;
    }

    public function save()
    {
        if (!$this->checkEmail() || !$this->passwordHash()) {
            return false;
        }

        return parent::save();
    }

    protected function checkEmail()
    {
        $check = null;

        if ($this->id) {
            $check = $this->select()
                ->where("email", "=", $this->email)
                ->where("id", "<>", $this->id)
                ->count();
        } else {
            $check = $this->select()
                ->where("email", "=", $this->email)
                ->count();
        }

        if ($check) {
            $this->error = new Exception("Este e-mail {$this->email} já está em uso.");
            return false;
        }

        return true;
    }

    protected function passwordHash()
    {
        if (empty($this->password) || strlen($this->password) < 6) {
            $this->error = new Exception("Sua senha precisa ter pelo menos 6 dígitos.");
            return false;
        }

        if (password_get_info($this->password)["algo"]) {
            return true;
        }

        $this->password = password_hash($this->password, CONF_PASSWORD_ALGO, CONF_PASSWORD_OPTION);
        return true;
    }
}

?>