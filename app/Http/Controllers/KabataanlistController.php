<?php

namespace App\Http\Controllers;
use App\Models\SkYouthForm;
use Carbon\Carbon;

use Illuminate\Http\Request;

class KabataanlistController extends Controller
{
    public function index()
    {
        $kabataan = SkYouthForm::all()->map(function ($youth) {
            $youth->age = Carbon::parse($youth->dob)->age;
            return $youth;
        });

        return view('users/kabataan-list', compact('kabataan'));
    }
}
