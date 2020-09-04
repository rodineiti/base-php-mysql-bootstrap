<?php
ob_start(); // control the application cache to have only 1 output

session_start();

$timezone = date_default_timezone_set("America/Sao_Paulo");

require "vendor/autoload.php";
require "config.php";
require "helpers.php";

(new Src\Core\Core())->run();

ob_end_flush(); // send the output and clear the cache