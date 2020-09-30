<?php

namespace Src\Controllers\Admin;

use Src\Core\Controller;

class HomeController extends Controller
{
    protected $data;

    public function __construct()
    {
        parent::__construct("admin/template");
        $this->data = array();
        $this->auth("admins");
    }

    public function index()
    {
        $this->template("admin/home", $this->data);
    }
}