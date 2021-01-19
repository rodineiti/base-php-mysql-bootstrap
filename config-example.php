<?php
ini_set("display_errors", 1);
ini_set("error_reporting", E_ALL);
ini_set('xdebug.overload_var_dump', 1);

require "env.php";

if (ENV === "development") {
    define("CONF_DB_HOST", "mysql");
    define("CONF_DB_USER", "root");
    define("CONF_DB_PASSOWRD", "root");
    define("CONF_DB_NAME", "database_base");
} else {
    define("CONF_DB_HOST", "mysql");
    define("CONF_DB_USER", "root");
    define("CONF_DB_PASSOWRD", "root");
    define("CONF_DB_NAME", "database_base");
}

define("CONF_DEFAULT_LANG", "pt-br");
define("BASE_URL", "http://localhost/base/");
define("BASE_URL_TEST", "http://localhost/base/");
define("SITE_NAME", "RDNBASE");

/**
 * PASSWORD
 */
define("CONF_PASSWORD_ALGO", PASSWORD_DEFAULT);
define("CONF_PASSWORD_OPTION", ["cost" => 10]);
define("CONF_JWT_SECRET", "123456879");

/**
 * UPLOADS
 */
define("CONF_UPLOAD_DIR", "media");
define("CONF_UPLOAD_FILE_AVATARS", CONF_UPLOAD_DIR . "/avatars");
define("CONF_UPLOAD_FILE_UPLOADS", CONF_UPLOAD_DIR . "/uploads");

/**
 * MAIL
 */
define("SENDBLUE_CONFIG", [
    "host" => "",
    "port" => "587",
    "user" => "",
    "password" => "",
    "from_name" => "",
    "from_email" => "",
    "from_support" => "",
    "lang" => "br",
    "html" => true,
    "auth" => true,
    "secure" => "tls",
    "charset" => "utf-8",
    "debug" => 1
]);