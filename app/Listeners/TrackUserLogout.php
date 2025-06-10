<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\UserSession;

class TrackUserLogout
{
    public function handle(Logout $event): void
    {
        UserSession::where('user_id', $event->user->id)
            ->where('is_active', true)
            ->latest()
            ->first()?->update([
                'is_active' => false,
                'last_activity' => now(),
            ]);
    }
}

