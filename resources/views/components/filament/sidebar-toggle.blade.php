<button
    type="button"
    x-data="{}"
    x-on:click="$store.sidebar.isOpen ? $store.sidebar.close() : $store.sidebar.open()"
    x-bind:aria-pressed="$store.sidebar.isOpen ? 'true' : 'false'"
    x-bind:aria-label="$store.sidebar.isOpen ? 'Collapse sidebar' : 'Expand sidebar'"
    x-bind:title="$store.sidebar.isOpen ? 'Collapse sidebar' : 'Expand sidebar'"
    x-bind:class="{ 'is-sidebar-collapsed': ! $store.sidebar.isOpen }"
    class="ac-sidebar-toggle"
>
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M4 7H20M4 12H20M4 17H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
    </svg>
</button>
