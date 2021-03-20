<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class API_TestingController extends Controller
{
    public function Testing(){
        return date("Y-M-d H:i:s");
    }
}
