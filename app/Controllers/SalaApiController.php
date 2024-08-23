<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class SalaApiController extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        return $this->respond(['this' => 'is sala']);
    }
}
