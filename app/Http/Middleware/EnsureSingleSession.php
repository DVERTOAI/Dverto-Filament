<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureSingleSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Filament::auth();
        $user = $guard->user();

        if (! $user) {
            return $next($request);
        }

        $currentSessionId = $request->session()->getId();

        if ($request->session()->pull('single_session.sync_required', false)) {
            $previousSessionId = $request->session()->pull('single_session.previous_session_id');

            if ($previousSessionId && ($previousSessionId !== $currentSessionId)) {
                DB::table(config('session.table', 'sessions'))
                    ->where('id', $previousSessionId)
                    ->delete();
            }

            $user->forceFill([
                'current_session_id' => $currentSessionId,
            ])->save();
        }

        if ($user->current_session_id !== $currentSessionId) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->guest(Filament::getLoginUrl());
        }

        return $next($request);
    }
}
