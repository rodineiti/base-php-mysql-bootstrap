<?php

namespace Src\Controllers\Admin;

use Src\Core\Controller;

class NotfoundController extends Controller
{
    public function __construct()
    {
        parent::__construct("admin/template");
    }

    public function index()
    {
        $this->template("error/404");
    }
}