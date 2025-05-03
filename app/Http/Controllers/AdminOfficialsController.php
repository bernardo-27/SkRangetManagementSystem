<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminOfficialsController extends Controller
{
    public function index()
    {
        return view('admin.officials.list');
    }
}
