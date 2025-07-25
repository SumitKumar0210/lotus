<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(){
        return view('backend.branch.dashboard');
    }
}
