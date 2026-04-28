<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureSingleSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SingleSessionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['web'])->get('/login', fn () => response('login'))->name('login');

        Route::middleware(['web'])->post('/test-login', function () {
            $credentials = request()->only('email', 'password');

            abort_unless(Auth::attempt($credentials), 401);

            request()->session()->regenerate();

            return response()->noContent();
        });

        Route::middleware(['web', EnsureSingleSession::class, 'auth'])->get('/test-protected', fn () => response('ok'));
    }

    public function test_new_login_replaces_previous_session(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
        ]);

        $firstBrowser = $this->withServerVariables(['HTTP_USER_AGENT' => 'browser-one']);
        $firstBrowser->post('/test-login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertNoContent();

        $firstSessionId = $user->fresh()->current_session_id;

        $secondBrowser = $this->withServerVariables(['HTTP_USER_AGENT' => 'browser-two']);
        $secondBrowser->post('/test-login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertNoContent();

        $user->refresh();

        $this->assertNotNull($firstSessionId);
        $this->assertNotSame($firstSessionId, $user->current_session_id);

        $firstBrowser->get('/test-protected')->assertRedirect('/admin/login');
    }
}
