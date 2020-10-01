<?php
ini_set("display_errors", 1);
ini_set("error_reporting", E_ALL);
ini_set('xdebug.overload_var_dump', 1);

require "env.php";

$config = array();

if (ENV === "development") {
    $config["dbname"] = "database_base";
    $config["dbhost"] = "mysql";
    $config["dbuser"] = "root";
    $config["dbpass"] = "root";
} else {
    $config["dbname"] = "database_base";
    $config["dbhost"] = "mysql";
    $config["dbuser"] = "root";
    $config["dbpass"] = "root";
}

define("CONF_DEFAULT_LANG", "pt-br");
define("BASE_URL", "http://localhost/base/");
define("SITE_NAME", "RDNBASE");

/**
 * PASSWORD
 */
define("CONF_PASSWORD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWORD_OPTION", ["cost" => 10]);
define("CONF_JWT_SECRET", "123456879");
define("CONF_UPLOAD_DIR", "media");
define("CONF_UPLOAD_FILE_AVATARS", CONF_UPLOAD_DIR . "/avatars");

global $db;

try {
    $db = new PDO("mysql:dbname={$config["dbname"]};host={$config["dbhost"]}", $config["dbuser"], $config["dbpass"],
        [
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
} catch (PDOException $exception) {
    echo "ERROR DATABASE: " . $exception->getMessage();
    exit;
}