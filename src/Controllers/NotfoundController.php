<?php

namespace Src\Controllers;

use Src\Core\Controller;

class NotfoundController extends Controller
{
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->data = array();
    }

    public function index()
    {
        $this->template("error/404", $this->data);
    }
}