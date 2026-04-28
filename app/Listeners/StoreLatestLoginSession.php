<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class StoreLatestLoginSession
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        session()->put('single_session.previous_session_id', $user->current_session_id);
        session()->put('single_session.sync_required', true);
    }
}
