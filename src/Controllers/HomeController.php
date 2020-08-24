<?php

namespace Src\Controllers;

use Src\Core\Controller;

class HomeController extends Controller
{
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->data = array();
    }

    public function index()
    {
        $this->template("home", $this->data);
    }
}