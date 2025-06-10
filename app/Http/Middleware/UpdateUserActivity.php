<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserSession;
use Illuminate\Support\Facades\Auth;

class UpdateUserActivity
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            UserSession::where('user_id', Auth::id())
                ->where('is_active', true)
                ->latest()
                ->first()?->update([
                    'last_activity' => now(),
                ]);
        }

        return $next($request);
    }
}
