<?php

namespace Src\Models;

use Src\Core\Model;

/**
 * Class Admin
 * @package Src\Models
 */
class Admin extends Model
{
    /**
     * Admin constructor.
     */
    public function __construct()
    {
        parent::__construct("admins");
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
}

?>