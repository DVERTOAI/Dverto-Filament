@php
    $visibleLimit = $visibleLimit ?? 3;
    $items = collect($items ?? [])
        ->filter(fn ($item) => filled($item))
        ->values();
    $visibleItems = $items->take($visibleLimit);
    $hiddenItems = $items->slice($visibleLimit)->values();
    $hiddenCount = $hiddenItems->count();
@endphp

@if ($items->isEmpty())
    <span class="ac-access-empty">{{ $emptyLabel ?? 'None' }}</span>
@else
    <div class="ac-access-list">
        @foreach ($visibleItems as $item)
            <span class="ac-access-badge">{{ $item }}</span>
        @endforeach

        @if ($hiddenCount)
            <x-filament::dropdown
                class="ac-access-more-dropdown"
                placement="bottom-start"
                shift
                teleport
                width="xs"
            >
                <x-slot name="trigger">
                    <button
                        type="button"
                        class="ac-access-more-chip"
                        aria-label="Show {{ $hiddenCount }} more"
                    >
                        +{{ $hiddenCount }} more
                    </button>
                </x-slot>

                <div class="ac-access-menu">
                    <div class="ac-access-menu-title">
                        {{ $popoverTitle ?? 'More items' }}
                    </div>

                    <div class="ac-access-menu-list">
                        @foreach ($hiddenItems as $item)
                            <span class="ac-access-menu-badge">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>
            </x-filament::dropdown>
        @endif
    </div>
@endif
