<?php
ob_start(); // controlar o cache da aplicação para ter apenas 1 output

session_start();

$timezone = date_default_timezone_set("America/Sao_Paulo");

require "vendor/autoload.php";
require "config.php";
require "helpers.php";

(new Src\Core\Core())->run();

ob_end_flush(); // envia o output e limpa o cache