@props([
    'currentPageOptionProperty' => 'tableRecordsPerPage',
    'extremeLinks' => false,
    'paginator',
    'pageOptions' => [],
])

@php
    use Filament\Support\Enums\IconPosition;
    use Filament\Support\Icons\Heroicon;
    use Illuminate\Contracts\Pagination\CursorPaginator;

    $isRtl = __('filament-panels::layout.direction') === 'rtl';
    $isSimple = ! $paginator instanceof \Illuminate\Pagination\LengthAwarePaginator;

    if ($paginator instanceof CursorPaginator) {
        $previousWireClickAction = $paginator->onFirstPage()
            ? null
            : "setPage('{$paginator->previousCursor()->encode()}', '{$paginator->getCursorName()}')";
        $nextWireClickAction = $paginator->hasMorePages()
            ? "setPage('{$paginator->nextCursor()->encode()}', '{$paginator->getCursorName()}')"
            : null;
    } else {
        $previousWireClickAction = "previousPage('{$paginator->getPageName()}')";
        $nextWireClickAction = "nextPage('{$paginator->getPageName()}')";
    }
@endphp

<nav
    aria-label="{{ __('filament::components/pagination.label') }}"
    role="navigation"
    {{
        $attributes->class([
            'fi-pagination',
            'fi-simple' => $isSimple,
        ])
    }}
>
    <x-filament::button
        color="gray"
        :disabled="$paginator->onFirstPage()"
        :icon="$isRtl ? Heroicon::OutlinedArrowRight : Heroicon::OutlinedArrowLeft"
        rel="prev"
        :wire:click="$paginator->onFirstPage() ? null : $previousWireClickAction"
        :wire:key="$this->getId() . '.pagination.previous'"
        class="fi-pagination-previous-btn"
    >
        {{ __('filament::components/pagination.actions.previous.label') }}
    </x-filament::button>

    @if (! $isSimple)
        <span class="fi-pagination-overview">
            {{
                trans_choice(
                    'filament::components/pagination.overview',
                    $paginator->total(),
                    [
                        'first' => \Illuminate\Support\Number::format($paginator->firstItem() ?? 0),
                        'last' => \Illuminate\Support\Number::format($paginator->lastItem() ?? 0),
                        'total' => \Illuminate\Support\Number::format($paginator->total()),
                    ],
                )
            }}
        </span>
    @endif

    @if (count($pageOptions) > 1)
        <div class="fi-pagination-records-per-page-select-ctn">
            <label class="fi-pagination-records-per-page-select fi-compact">
                <x-filament::input.wrapper>
                    <x-filament::input.select
                        :wire:model.live="$currentPageOptionProperty"
                    >
                        @foreach ($pageOptions as $option)
                            <option value="{{ $option }}">
                                {{ $option === 'all' ? __('filament::components/pagination.fields.records_per_page.options.all') : $option }}
                            </option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>

                <span class="fi-sr-only">
                    {{ __('filament::components/pagination.fields.records_per_page.label') }}
                </span>
            </label>

            <label class="fi-pagination-records-per-page-select">
                <x-filament::input.wrapper
                    :prefix="__('filament::components/pagination.fields.records_per_page.label')"
                >
                    <x-filament::input.select
                        :wire:model.live="$currentPageOptionProperty"
                    >
                        @foreach ($pageOptions as $option)
                            <option value="{{ $option }}">
                                {{ $option === 'all' ? __('filament::components/pagination.fields.records_per_page.options.all') : $option }}
                            </option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </label>
        </div>
    @endif

    <x-filament::button
        color="gray"
        :disabled="! $paginator->hasMorePages()"
        :icon="$isRtl ? Heroicon::OutlinedArrowLeft : Heroicon::OutlinedArrowRight"
        :icon-position="IconPosition::After"
        rel="next"
        :wire:click="$paginator->hasMorePages() ? $nextWireClickAction : null"
        :wire:key="$this->getId() . '.pagination.next'"
        class="fi-pagination-next-btn"
    >
        {{ __('filament::components/pagination.actions.next.label') }}
    </x-filament::button>

    @if ((! $isSimple) && $paginator->hasPages())
        <ol class="fi-pagination-items">
            @if (! $paginator->onFirstPage())
                @if ($extremeLinks)
                    <x-filament::pagination.item
                        :aria-label="__('filament::components/pagination.actions.first.label')"
                        :icon="$isRtl ? Heroicon::ChevronDoubleRight : Heroicon::ChevronDoubleLeft"
                        :icon-alias="
                            $isRtl
                            ? \Filament\Support\View\SupportIconAlias::PAGINATION_FIRST_BUTTON_RTL
                            : \Filament\Support\View\SupportIconAlias::PAGINATION_FIRST_BUTTON
                        "
                        rel="first"
                        :wire:click="'gotoPage(1, \'' . $paginator->getPageName() . '\')'"
                        :wire:key="$this->getId() . '.pagination.first'"
                    />
                @endif

                <x-filament::pagination.item
                    :aria-label="__('filament::components/pagination.actions.previous.label')"
                    :icon="$isRtl ? Heroicon::ChevronRight : Heroicon::ChevronLeft"
                    :icon-alias="
                        $isRtl
                        ? [
                            \Filament\Support\View\SupportIconAlias::PAGINATION_PREVIOUS_BUTTON_RTL,
                            \Filament\Support\View\SupportIconAlias::PAGINATION_PREVIOUS_BUTTON,
                        ]
                        : \Filament\Support\View\SupportIconAlias::PAGINATION_PREVIOUS_BUTTON
                    "
                    rel="prev"
                    :wire:click="'previousPage(\'' . $paginator->getPageName() . '\')'"
                    :wire:key="$this->getId() . '.pagination.previous'"
                />
            @endif

            @foreach ($paginator->render()->offsetGet('elements') as $element)
                @if (is_string($element))
                    <x-filament::pagination.item disabled :label="$element" />
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <x-filament::pagination.item
                            :active="$page === $paginator->currentPage()"
                            :aria-label="trans_choice('filament::components/pagination.actions.go_to_page.label', $page, ['page' => \Illuminate\Support\Number::format($page)])"
                            :label="\Illuminate\Support\Number::format($page)"
                            :wire:click="'gotoPage(' . $page . ', \'' . $paginator->getPageName() . '\')'"
                            :wire:key="$this->getId() . '.pagination.' . $paginator->getPageName() . '.' . $page"
                        />
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <x-filament::pagination.item
                    :aria-label="__('filament::components/pagination.actions.next.label')"
                    :icon="$isRtl ? Heroicon::ChevronLeft : Heroicon::ChevronRight"
                    :icon-alias="
                        $isRtl
                        ? [
                            \Filament\Support\View\SupportIconAlias::PAGINATION_NEXT_BUTTON_RTL,
                            \Filament\Support\View\SupportIconAlias::PAGINATION_NEXT_BUTTON,
                        ]
                        : \Filament\Support\View\SupportIconAlias::PAGINATION_NEXT_BUTTON
                    "
                    rel="next"
                    :wire:click="'nextPage(\'' . $paginator->getPageName() . '\')'"
                    :wire:key="$this->getId() . '.pagination.next'"
                />

                @if ($extremeLinks)
                    <x-filament::pagination.item
                        :aria-label="__('filament::components/pagination.actions.last.label')"
                        :icon="$isRtl ? Heroicon::ChevronDoubleLeft : Heroicon::ChevronDoubleRight"
                        :icon-alias="
                            $isRtl
                            ? \Filament\Support\View\SupportIconAlias::PAGINATION_LAST_BUTTON_RTL
                            : \Filament\Support\View\SupportIconAlias::PAGINATION_LAST_BUTTON
                        "
                        rel="last"
                        :wire:click="'gotoPage(' . $paginator->lastPage() . ', \'' . $paginator->getPageName() . '\')'"
                        :wire:key="$this->getId() . '.pagination.last'"
                    />
                @endif
            @endif
        </ol>
    @endif
</nav>
