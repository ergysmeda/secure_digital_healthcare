<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckConfirmation
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && !$user->isEmailConfirmed()) {
            return response()->json(['message' => 'Email confirmation required.'], 403);
        }

        return $next($request);
    }
}
