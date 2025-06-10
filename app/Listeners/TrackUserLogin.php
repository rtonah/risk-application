<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\UserSession;

class TrackUserLogin
{
    public function handle(Login $event): void
    {
        UserSession::create([
            'user_id' => $event->user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'last_activity' => now(),
            'is_active' => true,
        ]);
    }
}

