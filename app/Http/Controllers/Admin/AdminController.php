<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SkYouthForm;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index() {
        $totalUsers = SkYouthForm::count();
        $totalVoters = SkYouthForm::where('voter_status', 'yes')->count();
        $totalNonVoters = SkYouthForm::where('voter_status', 'no')->count();
        $maleCount = SkYouthForm::where('gender', 'male')->count();
        $femaleCount = SkYouthForm::where('gender', 'female')->count();

        // Initialize age group ranges
        $ageRanges = ['15-18', '19-22', '23-30'];
        $ageGroups = [
            'male' => array_fill_keys($ageRanges, 0),
            'female' => array_fill_keys($ageRanges, 0),
        ];


        $users = SkYouthForm::select('gender', 'dob')->get();
        foreach ($users as $user) {
            $age = Carbon::parse($user->dob)->age;


            foreach ($ageRanges as $range) {
                [$min, $max] = explode('-', $range);

                if ($age >= $min && $age <= $max) {
                    $gender = strtolower($user->gender);
                    if (array_key_exists($gender, $ageGroups)) {
                        $ageGroups[$gender][$range]++;
                    }
                }
            }
        }

        return view('admin.dashboard', compact('totalUsers', 'totalVoters', 'totalNonVoters', 'maleCount', 'femaleCount', 'ageGroups'));
    }
}


