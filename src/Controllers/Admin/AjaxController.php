<?php

namespace Src\Controllers\Admin;

use Src\Core\Controller;

class AjaxController extends Controller
{
    public function __construct()
    {
        parent::__construct("admin/template");
    }
}