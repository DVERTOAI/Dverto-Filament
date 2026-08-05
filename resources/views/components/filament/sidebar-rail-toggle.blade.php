{{-- Lives in the topbar brand zone; shown only when the sidebar is collapsed. --}}
<button
    type="button"
    x-data="{}"
    x-on:click="$store.sidebar.isOpen ? $store.sidebar.close() : $store.sidebar.open()"
    x-bind:aria-pressed="$store.sidebar.isOpen ? 'true' : 'false'"
    x-bind:aria-label="$store.sidebar.isOpen ? 'Collapse sidebar' : 'Expand sidebar'"
    x-bind:title="$store.sidebar.isOpen ? 'Collapse sidebar' : 'Expand sidebar'"
    x-bind:class="{ 'is-sidebar-collapsed': ! $store.sidebar.isOpen }"
    class="ac-sidebar-toggle ac-sidebar-toggle--rail"
></button>
