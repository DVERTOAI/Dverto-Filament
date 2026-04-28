<?php

namespace App\Listeners;

use Illuminate\Auth\Events\CurrentDeviceLogout;
use Illuminate\Auth\Events\Logout;

class ClearLatestLoginSession
{
    public function handle(Logout|CurrentDeviceLogout $event): void
    {
        $user = $event->user;

        if (! $user) {
            return;
        }

        $sessionId = session()->getId();

        if ($user->current_session_id !== $sessionId) {
            return;
        }

        $user->forceFill([
            'current_session_id' => null,
        ])->save();
    }
}
