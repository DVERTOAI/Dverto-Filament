<script data-navigate-once>
    (() => {
        if (window.__filamentSidebarAccordionInitialized) {
            return;
        }

        window.__filamentSidebarAccordionInitialized = true;

        const getSidebarStore = () => window.Alpine?.store?.('sidebar');

        const getCollapsibleGroupLabels = () =>
            Array.from(document.querySelectorAll('.fi-main-sidebar .fi-sidebar-group.fi-collapsible[data-group-label]'))
                .map((group) => group.dataset.groupLabel)
                .filter(Boolean);

        const getActiveGroupLabel = () =>
            document.querySelector('.fi-main-sidebar .fi-sidebar-group.fi-collapsible.fi-active[data-group-label]')
                ?.dataset.groupLabel;

        const syncCollapsedGroups = () => {
            const sidebar = getSidebarStore();

            if (! sidebar) {
                return;
            }

            const allGroups = getCollapsibleGroupLabels();
            const activeGroup = getActiveGroupLabel();

            sidebar.collapsedGroups = activeGroup
                ? allGroups.filter((group) => group !== activeGroup)
                : allGroups;
        };

        const patchSidebarStore = () => {
            const sidebar = getSidebarStore();

            if (! sidebar || sidebar.__accordionPatched) {
                return;
            }

            sidebar.groupIsCollapsed = (group) => {
                const collapsedGroups = Array.isArray(sidebar.collapsedGroups)
                    ? sidebar.collapsedGroups
                    : getCollapsibleGroupLabels();

                return collapsedGroups.includes(group);
            };

            sidebar.toggleCollapsedGroup = (group) => {
                const allGroups = getCollapsibleGroupLabels();

                if (! allGroups.includes(group)) {
                    return;
                }

                const collapsedGroups = Array.isArray(sidebar.collapsedGroups)
                    ? sidebar.collapsedGroups
                    : allGroups;

                sidebar.collapsedGroups = collapsedGroups.includes(group)
                    ? allGroups.filter((label) => label !== group)
                    : allGroups;
            };

            sidebar.__accordionPatched = true;
        };

        const syncSidebarAccordion = () => {
            patchSidebarStore();
            syncCollapsedGroups();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', syncSidebarAccordion, { once: true });
        } else {
            syncSidebarAccordion();
        }

        document.addEventListener('livewire:navigated', syncSidebarAccordion);

        const applyTheme = (theme) => {
            const resolvedTheme = theme ?? localStorage.getItem('theme') ?? 'light';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = resolvedTheme === 'dark' || (resolvedTheme === 'system' && prefersDark);

            window.theme = resolvedTheme;
            document.documentElement.classList.toggle('dark', isDark);
            window.dispatchEvent(new CustomEvent('ac-theme-changed', {
                detail: { theme: resolvedTheme, isDark },
            }));
        };

        window.acDashboardTheme = {
            get() {
                return localStorage.getItem('theme') ?? 'light';
            },

            set(theme) {
                localStorage.setItem('theme', theme);
                applyTheme(theme);
            },

            toggle() {
                this.set(document.documentElement.classList.contains('dark') ? 'light' : 'dark');
            },
        };

        applyTheme(window.acDashboardTheme.get());
    })();
</script>
