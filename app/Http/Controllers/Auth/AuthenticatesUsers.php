<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthenticatesUsers extends Controller
{
    protected function redirectTo()
{
    return auth()->user()->hasCompletedForm() ? route('dashboard') : route('sk-youth-form');
}

}
