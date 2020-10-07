<?php

namespace Src\Core;

use \PDO;
use \PDOException;

/**
 * Class Connection
 * @package Src\Core
 */
class Connection
{
    /**
     * Pattern Singleton
     */
    private static $instance;

    /**
     * FINAL disabled new instance
     */
    final private function __construct()
    {}

    /**
     * FINAL disabled new instance
     */
    final private function __clone()
    {}

    /**
     * @return PDO
     */
    public static function getInstance(): ?PDO
    {
        if (empty(self::$instance)) {
            try {
                self::$instance = new PDO(
                    "mysql:dbname=".CONF_DB_NAME.";host=".CONF_DB_HOST, 
                    CONF_DB_USER, 
                    CONF_DB_PASSOWRD,
                    [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                        PDO::ATTR_CASE => PDO::CASE_NATURAL
                    ]);
            } catch(PDOException $exception) {
                die($exception->getMessage());
            }
        }

        return self::$instance;
    }
}