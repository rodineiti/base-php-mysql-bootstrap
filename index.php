<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

session_start();

$timezone = date_default_timezone_set("America/Sao_Paulo");

require __DIR__ . "/vendor/autoload.php";

try {
    require __DIR__ . '/routes/web.php';
} catch (\Exception $exception) {
    echo $exception->getMessage();
}

resolve();