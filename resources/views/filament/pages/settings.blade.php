<x-filament-panels::page>
<div class="ac-st">
<form wire:submit.prevent="save" class="ac-st-form">

    {{-- ── Brand ── --}}
    <div class="ac-st-section">
        <div class="ac-st-section-hd">
            <span class="ac-st-section-icon">
                <x-filament::icon icon="heroicon-o-building-office-2" class="ac-st-ico" />
            </span>
            <div>
                <h3 class="ac-st-section-title">Brand</h3>
                <p class="ac-st-section-desc">Workspace name shown in the sidebar and browser tab.</p>
            </div>
        </div>
        <div class="ac-st-fields">
            <div class="ac-st-field ac-st-field--half">
                <label class="ac-st-label" for="brand_name">Brand Name</label>
                <input id="brand_name" type="text" wire:model="brand_name"
                    class="ac-st-input" placeholder="e.g. Dverto" />
                @error('brand_name')<p class="ac-st-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="ac-st-divider"></div>

    {{-- ── Profile ── --}}
    <div class="ac-st-section">
        <div class="ac-st-section-hd">
            <span class="ac-st-section-icon">
                <x-filament::icon icon="heroicon-o-user-circle" class="ac-st-ico" />
            </span>
            <div>
                <h3 class="ac-st-section-title">Profile</h3>
                <p class="ac-st-section-desc">Your display name and login email address.</p>
            </div>
        </div>
        <div class="ac-st-fields">
            <div class="ac-st-field">
                <label class="ac-st-label" for="profile_name">Full Name</label>
                <input id="profile_name" type="text" wire:model="profile_name"
                    class="ac-st-input" placeholder="John Doe" />
                @error('profile_name')<p class="ac-st-error">{{ $message }}</p>@enderror
            </div>
            <div class="ac-st-field">
                <label class="ac-st-label" for="profile_email">Email Address</label>
                <input id="profile_email" type="email" wire:model="profile_email"
                    class="ac-st-input" placeholder="you@example.com" />
                @error('profile_email')<p class="ac-st-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="ac-st-divider"></div>

    {{-- ── Security ── --}}
    <div class="ac-st-section">
        <div class="ac-st-section-hd">
            <span class="ac-st-section-icon">
                <x-filament::icon icon="heroicon-o-lock-closed" class="ac-st-ico" />
            </span>
            <div>
                <h3 class="ac-st-section-title">Security</h3>
                <p class="ac-st-section-desc">Leave password fields blank to keep your current password.</p>
            </div>
        </div>
        <div class="ac-st-fields">
            <div class="ac-st-field ac-st-field--full">
                <label class="ac-st-label" for="current_password">Current Password</label>
                <input id="current_password" type="password" wire:model="current_password"
                    class="ac-st-input" placeholder="••••••••" autocomplete="current-password" />
                @error('current_password')<p class="ac-st-error">{{ $message }}</p>@enderror
            </div>
            <div class="ac-st-field">
                <label class="ac-st-label" for="new_password">New Password</label>
                <input id="new_password" type="password" wire:model="new_password"
                    class="ac-st-input" placeholder="Min. 8 characters" autocomplete="new-password" />
                @error('new_password')<p class="ac-st-error">{{ $message }}</p>@enderror
            </div>
            <div class="ac-st-field">
                <label class="ac-st-label" for="confirm_password">Confirm Password</label>
                <input id="confirm_password" type="password" wire:model="confirm_password"
                    class="ac-st-input" placeholder="Repeat new password" autocomplete="new-password" />
            </div>
        </div>

        {{-- Sessions --}}
        <div class="ac-st-danger-row">
            <div>
                <p class="ac-st-danger-title">Active Sessions</p>
                <p class="ac-st-danger-hint">Sign out from all other devices immediately.</p>
            </div>
            <button type="button" class="ac-st-ghost-danger"
                onclick="confirm('Clear all other sessions?') && @this.call('clearSessions')">
                Clear Sessions
            </button>
        </div>
    </div>

    {{-- ── Single Save ── --}}
    <div class="ac-st-footer">
        <button type="submit" class="ac-st-save">
            Save Changes
        </button>
    </div>

</form>
</div>
</x-filament-panels::page>
