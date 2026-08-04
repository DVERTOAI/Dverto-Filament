{{-- Feedback while the table is filtering, searching, sorting or paginating. --}}
<div
    class="ac-table-loading"
    wire:loading.class="ac-is-loading"
    wire:target="tableFilters, tableSearch, tableColumnSearches, removeTableFilter, removeTableFilters, resetTableFiltersForm, sortTable, tableRecordsPerPage, gotoPage, previousPage, nextPage"
>
    <span class="ac-table-loading-bar" aria-hidden="true"></span>

    <span class="ac-table-loading-pill" role="status" aria-live="polite">
        <span class="ac-table-loading-spinner" aria-hidden="true"></span>
        Updating results…
    </span>
</div>
