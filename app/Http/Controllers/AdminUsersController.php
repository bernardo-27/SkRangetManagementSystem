<?php

namespace App\Http\Controllers;
use APP\Models\User;
use Illuminate\Http\Request;

class AdminUsersController extends Controller
{
    public function index() {
        $users = User::select('id', 'name', 'email')
            ->where('usertype', '!=', 'admin') // Exclude admin account
            ->get();

        return view('admin.users', compact('users'));
    }


    public function updateStatus(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $user->status = $request->input('status'); // 'accepted' or 'rejected'
        $user->save();

        return response()->json(['success' => true]);
    }


public function destroy($id)
{
    $users = User::findOrFail($id);
    $users->delete();

    return redirect()->route('admin.users')->with('success', 'User account removed successfully.');
}

}

