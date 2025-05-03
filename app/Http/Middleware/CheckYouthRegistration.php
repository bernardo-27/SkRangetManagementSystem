<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SkYouthForm;
use Illuminate\Support\Facades\Auth;

class CheckYouthRegistration
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        $hasRegistered = SkYouthForm::where('user_id', $user->id)->exists();

        if (!$hasRegistered) {
            return redirect()->route('dashboard'); // Redirect to dashboard where modal shows
        }

        return $next($request);
    }
}

