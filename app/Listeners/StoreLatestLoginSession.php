<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StoreLatestLoginSession
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $previousSessionId = $user->current_session_id;

        session()->forget([
            'single_session.previous_session_id',
            'single_session.sync_required',
        ]);

        app()->terminating(function () use ($previousSessionId, $user): void {
            $currentSessionId = session()->getId();

            if ($previousSessionId && ($previousSessionId !== $currentSessionId)) {
                $this->deleteDatabaseSession($previousSessionId);
            }

            $user->forceFill([
                'current_session_id' => $currentSessionId,
            ])->save();
        });
    }

    protected function deleteDatabaseSession(string $sessionId): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        $table = config('session.table', 'sessions');

        if (! Schema::hasTable($table)) {
            return;
        }

        DB::table($table)
            ->where('id', $sessionId)
            ->delete();
    }
}
