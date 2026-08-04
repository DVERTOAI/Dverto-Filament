@php
    use Illuminate\Support\Str;

    $user = auth()->user();
    $name = $user?->name ?? 'Admin User';

    // Prefer already-loaded roles relation to avoid a query on every request.
    $roleName = $user?->relationLoaded('roles')
        ? $user->roles->first()?->name
        : $user?->getRoleNames()->first();

    $role = ($user?->hasRole('admin') ?? false)
        ? 'Super Admin'
        : Str::headline($roleName ?? 'Team Member');
@endphp

{{-- Icon at sidebar end border, welcome text immediately beside it --}}
<div class="ac-topbar-welcome">
    @include('components.filament.sidebar-toggle')

    <div class="ac-topbar-welcome-copy">
        <p class="ac-topbar-welcome-text">
            Welcome back, <strong>{{ $name }}</strong>!
        </p>
        <span class="ac-topbar-welcome-badge">{{ $role }}</span>
    </div>
</div>
