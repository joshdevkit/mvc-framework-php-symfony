<?php

namespace App\Controller;

use App\Database\Database;
use App\Route\Controller as BaseController;


class Controller extends BaseController
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }
}
