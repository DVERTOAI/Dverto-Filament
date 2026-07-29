@php
    use Illuminate\Support\Str;

    $user = auth()->user();
    $name = $user?->name ?? 'Admin User';
    $role = $user?->hasRole('admin')
        ? 'Super Admin'
        : Str::headline($user?->roles()->pluck('name')->first() ?? 'Team Member');
    $initials = Str::of($name)
        ->explode(' ')
        ->filter()
        ->take(2)
        ->map(fn (string $part): string => Str::upper(Str::substr($part, 0, 1)))
        ->implode('');
@endphp

<div class="ac-topbar-welcome">
    <span class="ac-topbar-welcome-avatar" aria-hidden="true">{{ $initials }}</span>
    <div class="ac-topbar-welcome-copy">
        <p class="ac-topbar-welcome-text">
            Welcome back, <strong>{{ $name }}</strong>!
        </p>
        <span class="ac-topbar-welcome-badge">{{ $role }}</span>
    </div>
</div>
