<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Official;

class SKOfficialsController extends Controller
{
    public function index() {
        $officials = Official::all();
        return view('users.sk-officials', compact('officials'));


    }


    public function show($id)
{
    $official = Official::findOrFail($id);
    return view('users.sk-officials.show', compact('official'));
}
}
