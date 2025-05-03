<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureNoAdminExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if any admin user exists
        $adminExists = User::where('usertype', 'admin')->exists();
        
        if ($adminExists) {
            return redirect('/')->with('error', 'Admin setup has already been completed.');
        }

        return $next($request);
    }
}
