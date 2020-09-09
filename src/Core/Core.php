<?php

namespace Src\Core;

class Core
{
    public function run()
    {
        /**
         * param 1 - controller
         * param 2 - action
         * param 3 - params
         */
        $url = "/";
        if (isset($_GET["url"])) {
            $url .= filter_var($_GET["url"], FILTER_SANITIZE_SPECIAL_CHARS);
        }

        $prefix = "\Src\Controllers\\";
        $admin = false;

        if (!empty($url) && $url !== "/" && (in_array(substr($url, 1), check_url()) || count(explode("/", $url)) >= 4)) {
            $prefix = "\Src\Controllers\\Admin\\";
            $admin = true;
        }

        $controler = "HomeController";
        $action = "index";
        $params = array();

        if (!empty($url) && $url !== "/") {
            $url = explode("/", $url);

            if ($admin && count($url) >= 4) {
                array_shift($url); // remove first item
            }

            array_shift($url); // remove first item

            $controler = ucwords(strtolower($url[0]))."Controller";
            array_shift($url); // remove first item

            if (isset($url[0]) && !empty($url[0])) {
                $action = $url[0];
                array_shift($url); // remove first item
            } else {
                $action = "index";
            }

            if (count($url)) {
                $params = $url;
            }

            $file ="src/Controllers/{$controler}.php";
            if ($admin) {
                $file ="src/Controllers/Admin/{$controler}.php";
            }

            if (!file_exists($file) || !method_exists($prefix.$controler, $action)) {
                $controler = "NotfoundController";
                $action = "index";
            }

            //dd($prefix.$controler, $action, method_exists($prefix.$controler, $action));

            if (!method_exists($prefix.$controler, $action)) {
                $action = "index";
            }
        }


        $controler = $prefix.$controler;
        // perform the function of the controller along with the parameters
        call_user_func_array([new $controler(), $action], $params);
    }
}