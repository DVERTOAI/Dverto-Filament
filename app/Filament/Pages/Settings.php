<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\HasMinimalBreadcrumbs;
use App\Filament\Pages\Concerns\HasPagePermission;
use App\Support\AdminPermissions;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;

class Settings extends Page
{
    use HasMinimalBreadcrumbs;
    use HasPagePermission;

    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static ?string $navigationLabel = 'Settings';
    protected static ?int $navigationSort = 3;
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.settings';

    public string $brand_name       = '';
    public string $profile_name     = '';
    public string $profile_email    = '';
    public string $current_password = '';
    public string $new_password     = '';
    public string $confirm_password = '';

    protected static function requiredPermission(): string
    {
        return AdminPermissions::MANAGE_SETTINGS;
    }

    public function mount(): void
    {
        $this->brand_name    = config('app.name', 'Dverto');
        $this->profile_name  = auth()->user()?->name  ?? '';
        $this->profile_email = auth()->user()?->email ?? '';
    }

    public function save(): void
    {
        $rules = [
            'brand_name'    => 'required|string|max:80',
            'profile_name'  => 'required|string|max:255',
            'profile_email' => 'required|email|max:255',
        ];

        $changePassword = filled($this->new_password) || filled($this->current_password);

        if ($changePassword) {
            $rules['current_password'] = 'required';
            $rules['new_password']     = 'required|min:8|same:confirm_password';
        }

        $this->validate($rules);

        // Brand
        $envPath = base_path('.env');
        $env     = file_get_contents($envPath);
        $env     = preg_replace('/^APP_NAME=.*/m', 'APP_NAME="' . $this->brand_name . '"', $env);
        file_put_contents($envPath, $env);

        // Profile
        $user        = auth()->user();
        $user->name  = $this->profile_name;
        $user->email = $this->profile_email;

        // Password
        if ($changePassword) {
            if (! Hash::check($this->current_password, $user->password)) {
                $this->addError('current_password', 'Current password is incorrect.');
                return;
            }
            $user->password = Hash::make($this->new_password);
            $this->current_password = $this->new_password = $this->confirm_password = '';
        }

        $user->save();

        Notification::make()->title('Settings saved')->success()->send();
    }

    public function clearSessions(): void
    {
        auth()->user()->forceFill(['current_session_id' => null])->save();
        Notification::make()->title('Sessions cleared')->success()->send();
    }

    public function getHeading(): string    { return 'Settings'; }
    public function getSubheading(): ?string { return 'Manage your workspace, profile and security.'; }
}
