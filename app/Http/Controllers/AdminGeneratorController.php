<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminGeneratorController extends Controller
{
    /**
     * Show the admin setup form (first admin only)
     */
    public function showSetupForm()
    {
        return view('admin.setup');
    }

    /**
     * Handle the admin setup (first admin only)
     */
    public function setupAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'setup_key' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if setup key matches the one in .env
        if ($request->setup_key !== config('app.admin_setup_key')) {
            return back()->with('error', 'Invalid setup key. Please check your environment configuration.')->withInput();
        }

        // Create new admin user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => 'admin',
        ]);

        return redirect()->route('login')
            ->with('success', 'Admin account created successfully! You can now log in.');
    }

    /**
     * Show the form for generating additional admin accounts
     */
    public function showGenerateForm()
    {
        return view('admin.generate');
    }

    /**
     * Handle generating additional admin accounts
     */
    public function generateAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'security_key' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if security key matches the one in .env
        if ($request->security_key !== config('app.admin_security_key')) {
            return back()->with('error', 'Invalid security key.')->withInput();
        }

        // Create new admin user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => 'admin',
        ]);

        return back()->with('success', 'Admin account created successfully!');
    }
}